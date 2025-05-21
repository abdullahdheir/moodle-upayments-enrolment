<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');


$instanceid = required_param('instanceid', PARAM_INT);
$orderid = required_param('orderid', PARAM_ALPHANUM);
$trackid = required_param('track_id', PARAM_ALPHANUM);

// Get raw POST data
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

// Log the notification
error_log("Upayment notification received: " . $raw_data);

// Verify payment
if (!enrol_upayment_verify_payment($trackid)) {
    http_response_code(400);
    die('Payment verification failed');
}

// Check if payment was already processed
$existing = $DB->get_record('enrol_upayment_transactions', array('track_id' => $trackid));
if ($existing) {
    http_response_code(200);
    die('Payment already processed');
}

// Get instance and user
$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$user = $DB->get_record('user', array('id' => $data['customer']['uniqueId']), '*', MUST_EXIST);

// Log transaction with all UPayments parameters
enrol_upayment_log_transaction($user->id, $instanceid, $data);

// Enrol user
$plugin = enrol_get_plugin('upayment');
$plugin->enrol_user_via_callback($instanceid, $user->id, $trackid, $instance->cost);

http_response_code(200);
die('OK'); 