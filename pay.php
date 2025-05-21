<?php
require_once('../../config.php');
require_once($CFG->libdir.'/enrollib.php');
require_once('lib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);

require_sesskey();
require_login();

$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

// Check if user is already enrolled
if (is_enrolled($context, $USER)) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
}

// Get the course cost from teacher settings
$teacher_cost = $DB->get_field('enrol_upayment_costs', 'cost', 
    ['courseid' => $instance->courseid, 'userid' => $instance->customint1]);

// If no teacher cost set, use default cost
$cost = $teacher_cost ?: $instance->cost;

// Prepare payment data
$payment_data = array(
    'order_id' => uniqid('MOODLE_'),
    'customer' => array(
        'uniqueId' => $USER->id,
        'name' => fullname($USER),
        'email' => $USER->email
    ),
    'products' => array(
        array(
            'name' => $course->fullname,
            'price' => $cost,
            'quantity' => 1
        )
    ),
    'currency' => $instance->currency,
    'amount' => $cost,
    'redirect_url' => $CFG->wwwroot . '/enrol/upayment/notification.php',
    'cancel_url' => $CFG->wwwroot . '/course/view.php?id=' . $course->id
);

try {
    // Make API request
    $response = enrol_upayment_make_api_request('charge', $payment_data);
    
    if (isset($response['data']['link'])) {
        // Redirect to payment page
        redirect($response['data']['link']);
    } else {
        throw new moodle_exception('payment_link_not_found', 'enrol_upayment');
    }
} catch (Exception $e) {
    // Log error and show message to user
    error_log("Payment initiation failed: " . $e->getMessage());
    $PAGE->set_url('/enrol/upayment/pay.php');
    $PAGE->set_context($context);
    $PAGE->set_title(get_string('payment_error', 'enrol_upayment'));
    echo $OUTPUT->header();
    echo $OUTPUT->error_text(get_string('payment_error', 'enrol_upayment'));
    echo $OUTPUT->continue_button(new moodle_url('/course/view.php', array('id' => $course->id)));
    echo $OUTPUT->footer();
}
