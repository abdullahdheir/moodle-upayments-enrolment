<?php
require_once('../../config.php');
require_once($CFG->libdir.'/enrollib.php');
require_once('lib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$orderid = required_param('orderid', PARAM_ALPHANUM);
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

// Log transaction
enrol_upayment_log_transaction($USER->id, $instanceid, $trackid, $instance->cost);

// Enrol user
$plugin = enrol_get_plugin('upayment');
$plugin->enrol_user_via_callback($instanceid, $USER->id, $trackid, $instance->cost);

// Show success message before redirecting
redirect(new moodle_url('/enrol/upayment/processing.php', 
    array('instanceid' => $instanceid, 'sesskey' => sesskey(), 'status' => 'success'))); 