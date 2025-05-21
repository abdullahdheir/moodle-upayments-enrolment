<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * UPayments enrolment plugin main class and helper functions.
 *
 * This plugin enables paid course enrolment in Moodle using the UPayments payment gateway.
 *
 * @package    enrol_upayment
 * @copyright  2025 Abdullah Dheir (https://github.com/abdullahdheir)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @website    https://upayments.com
 */

defined('MOODLE_INTERNAL') || die();

class enrol_upayment_plugin extends enrol_plugin {
    public function enrol_page_hook(stdClass $instance) {
        global $USER, $OUTPUT, $DB;
        if (isguestuser() || !isloggedin()) {
            return '';
        }
        $context = context_course::instance($instance->courseid);
        if (is_enroled($context, $USER)) {
            return '';
        }
        // Get the course cost from teacher settings
        $teacher_cost = $DB->get_field('enrol_upayment_costs', 'cost',
            ['courseid' => $instance->courseid, 'userid' => $instance->customint1]);
        // If no teacher cost set, use default cost
        $cost = $teacher_cost ?: $instance->cost;
        $cost = format_float($cost, 2, true) . ' ' . $instance->currency;
        $url = new moodle_url('/enrol/upayment/pay.php', ['instanceid' => $instance->id, 'sesskey' => sesskey()]);
        $btn = new single_button($url, get_string('paybutton', 'enrol_upayment'));
        $html = html_writer::tag('p', get_string('enrolcost', 'enrol_upayment').": ".$cost);
        return $html.$OUTPUT->render($btn);
    }
    public function enrol_user_via_callback($instanceid, $userid, $trackid, $amount) {
        global $DB;
        $instance = $DB->get_record('enrol', ['id' => $instanceid], '*', MUST_EXIST);
        $this->enrol_user($instance, $userid, $instance->roleid, time(), 0, ENROL_USER_ACTIVE);
        // Log transaction
        $DB->insert_record('enrol_upayment_transactions', [
            'userid' => $userid,
            'instanceid' => $instanceid,
            'trackid' => $trackid,
            'amount' => $amount,
            'timecreated' => time()
        ]);
    }
    public function add_default_instance($course) {
        $fields = array(
            'status'          => $this->get_config('status'),
            'roleid'          => $this->get_config('roleid', 0),
            'enrolperiod'     => 0,
            'expirynotify'    => $this->get_config('expirynotify'),
            'expirythreshold' => $this->get_config('expirythreshold', 86400),
            'cost'            => $this->get_config('cost'),
            'currency'        => $this->get_config('currency', 'KWD'),
            'customint1'      => 0, // Teacher ID
        );
        return $this->add_instance($course, $fields);
    }
    public function add_instance($course, array $fields = NULL) {
        $fields = (array)$fields;
        if (!isset($fields['cost'])) {
            $fields['cost'] = $this->get_config('cost');
        }
        if (!isset($fields['currency'])) {
            $fields['currency'] = $this->get_config('currency', 'KWD');
        }
        if (!isset($fields['customint1'])) {
            $fields['customint1'] = 0; // Teacher ID
        }
        return parent::add_instance($course, $fields);
    }
    public function get_manual_enrol_button(course_enrolment_manager $manager) {
        $instance = null;
        $instances = array();
        foreach ($manager->get_enrolment_instances() as $tempinstance) {
            if ($tempinstance->enrol == 'upayment') {
                if ($instance === null) {
                    $instance = $tempinstance;
                }
                $instances[] = $tempinstance;
            }
        }
        if (empty($instance)) {
            return null;
        }
        $context = $manager->get_context();
        if (!has_capability('enrol/upayment:config', $context)) {
            return null;
        }
        $button = new enrol_user_button($instance, $context);
        return $button;
    }
    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;
        if ($instance->enrol !== 'upayment') {
            throw new coding_exception('invalid enrol instance!');
        }
        $context = context_course::instance($instance->courseid);
        $icons = array();
        if (has_capability('enrol/upayment:config', $context)) {
            $editlink = new moodle_url("/enrol/upayment/edit.php", array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core',
                    array('class' => 'iconsmall')));
        }
        return $icons;
    }
}

function enrol_upayment_make_api_request($endpoint, $data = null) {
    global $CFG;
    
    try {
        // Get API settings
        $sandbox = get_config('enrol_upayment', 'sandbox');
        
        if ($sandbox) {
            // Use sandbox settings
            $token = get_config('enrol_upayment', 'sandbox_token');
            $apiurl = get_config('enrol_upayment', 'sandbox_apiurl');
            error_log("UPayments Sandbox Mode: Enabled");
        } else {
            // Use production settings
            $token = get_config('enrol_upayment', 'token');
            $apiurl = get_config('enrol_upayment', 'apiurl');
        }
        
        if (empty($token)) {
            throw new moodle_exception('token_not_configured', 'enrol_upayment');
        }
        
        // Set the API URL
        $url = $apiurl . $endpoint;
        
        // Initialize curl
        $curl = new curl();
        $curl->setHeader([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);
        
        // Make the request
        $response = $curl->post($url, json_encode($data));
        
        // Check for curl errors
        if ($curl->get_errno()) {
            error_log("UPayments API Error: " . $curl->error);
            throw new moodle_exception('api_error', 'enrol_upayment', '', $curl->error);
        }
        
        // Decode response
        $result = json_decode($response, true);
        
        // Log the API response for debugging
        error_log("UPayments API Response: " . $response);
        
        if (!isset($result['status']) || !$result['status']) {
            error_log("UPayments API Error: " . ($result['message'] ?? 'Unknown error'));
            throw new moodle_exception('api_error', 'enrol_upayment', '', $result['message'] ?? 'Unknown error');
        }
        
        return $result;
        
    } catch (Exception $e) {
        error_log("UPayments API Exception: " . $e->getMessage());
        throw new moodle_exception('api_error', 'enrol_upayment', '', $e->getMessage());
    }
}

function enrol_upayment_verify_payment($trackid) {
    try {
        $response = enrol_upayment_make_api_request('get-payment-status/' . $trackid);
        return isset($response['data']['transaction']) && $response['data']['transaction']['status'] === 'success';
    } catch (Exception $e) {
        error_log("Payment verification failed: " . $e->getMessage());
        return false;
    }
}

function enrol_upayment_log_transaction($userid, $instanceid, $data) {
    global $DB;
    
    $instance = $DB->get_record('enrol', ['id' => $instanceid], '*', MUST_EXIST);
    $sandbox = get_config('enrol_upayment', 'sandbox');
    
    $transaction = new stdClass();
    $transaction->userid = $userid;
    $transaction->instanceid = $instanceid;
    $transaction->amount = $instance->cost;
    $transaction->currency = $instance->currency;
    $transaction->timecreated = time();
    $transaction->status = isset($data['result']) ? $data['result'] : 'pending';
    $transaction->is_sandbox = $sandbox ? 1 : 0;
    
    // Add all UPayments parameters
    $upayment_fields = [
        'payment_id', 'result', 'post_date', 'tran_id', 'ref', 'track_id',
        'auth', 'order_id', 'requested_order_id', 'refund_order_id',
        'payment_type', 'invoice_id', 'transaction_date', 'receipt_id', 'trn_udf'
    ];
    
    foreach ($upayment_fields as $field) {
        if (isset($data[$field])) {
            $transaction->$field = $data[$field];
        }
    }
    
    return $DB->insert_record('enrol_upayment_transactions', $transaction);
} 