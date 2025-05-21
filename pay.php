<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);

require_sesskey();
require_login();

$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

// Check if user is already enroled
if (is_enrolled($context, $USER)) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
}

// Use the instance cost directly
$cost = $instance->cost;

// Prepare payment data in UPayments API format
$orderid = uniqid('MOODLE_UPAYMENT_COURSE_'.$course->id.'_');
$payment_data = [
    'products' => [
        [
            'name' => $course->fullname,
            'price' => $cost,
            'quantity' => 1
        ]
    ],
    'order' => [
        'id' => $orderid,
        'description' => get_string('orderdescription', 'enrol_upayment', $course->fullname),
        'currency' => $instance->currency,
        'amount' => $cost,
    ],
    'reference' => [
        'id' => $orderid
    ],
    'paymentGateway' => [
        'src' => 'knet',
    ],
    'customer' => [
        'uniqueId' => $USER->id,
        'name' => fullname($USER),
        'email' => $USER->email,
    ],
    'language' => current_language(),
    'returnUrl' => $CFG->wwwroot . '/enrol/upayment/success.php?instanceid=' . $instance->id . '&sesskey=' . sesskey(),
    'cancelUrl' => $CFG->wwwroot . '/enrol/upayment/cancel.php?instanceid=' . $instance->id . '&sesskey=' . sesskey(),
    'notificationUrl' => $CFG->wwwroot . '/enrol/upayment/notification.php',
];

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
