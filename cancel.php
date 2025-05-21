<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$instanceid = required_param('instanceid', PARAM_INT);

require_login();

$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);

// Redirect back to course with error message
redirect(new moodle_url('/course/view.php', array('id' => $course->id)), 
    get_string('payment_cancelled', 'enrol_upayment'), 
    null, 
    \core\output\notification::NOTIFY_ERROR
); 