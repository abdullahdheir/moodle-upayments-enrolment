<?php
defined('MOODLE_INTERNAL') || die();

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