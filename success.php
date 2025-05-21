<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$orderid = required_param('order_id', PARAM_ALPHANUM);
$trackid = required_param('track_id', PARAM_ALPHANUM);

require_login();

$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

// Show processing page while verifying
redirect(new moodle_url('/enrol/upayment/processing.php', 
    array('instanceid' => $instanceid, 'sesskey' => sesskey(), 'status' => 'verifying')));

// Verify payment
if (!enrol_upayment_verify_payment($trackid)) {
    redirect(new moodle_url('/enrol/upayment/processing.php', 
        array('instanceid' => $instanceid, 'sesskey' => sesskey(), 'status' => 'error')));
}

// Check if payment was already processed
$existing = $DB->get_record('enrol_upayment_transactions', array('trackid' => $trackid));
if ($existing) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
}

// Collect all parameters from the URL for logging
$transaction_data = [
    'payment_id' => optional_param('?payment_id', null, PARAM_ALPHANUM),
    'result' => optional_param('result', null, PARAM_ALPHANUM),
    'post_date' => optional_param('post_date', null, PARAM_TEXT),
    'tran_id' => optional_param('tran_id', null, PARAM_ALPHANUM),
    'ref' => optional_param('ref', null, PARAM_ALPHANUM),
    'track_id' => $trackid,
    'auth' => optional_param('auth', null, PARAM_ALPHANUM),
    'order_id' => $orderid,
    'requested_order_id' => optional_param('requested_order_id', null, PARAM_ALPHANUM),
    'refund_order_id' => optional_param('refund_order_id', null, PARAM_ALPHANUM),
    'payment_type' => optional_param('payment_type', null, PARAM_ALPHANUM),
    'invoice_id' => optional_param('invoice_id', null, PARAM_ALPHANUM),
    'transaction_date' => optional_param('transaction_date', null, PARAM_TEXT),
    'receipt_id' => optional_param('receipt_id', null, PARAM_ALPHANUM),
    'trn_udf' => optional_param('trn_udf', null, PARAM_TEXT),
    'amount' => $instance->cost,
    'currency' => $instance->currency,
];

// Log transaction
enrol_upayment_log_transaction($USER->id, $instanceid, $transaction_data);

// Enrol user
$plugin = enrol_get_plugin('upayment');
$plugin->enrol_user_via_callback($instanceid, $USER->id, $trackid, $instance->cost);

// Show success message before redirecting
redirect(new moodle_url('/enrol/upayment/processing.php', 
    array('instanceid' => $instanceid, 'sesskey' => sesskey(), 'status' => 'success'))); 