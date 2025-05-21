<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);
$status = optional_param('status', 'processing', PARAM_ALPHANUM);

// Get UPayments return parameters if available
$upayments_params = [
    'payment_id' => optional_param('payment_id', null, PARAM_ALPHANUM),
    'result' => optional_param('result', null, PARAM_ALPHANUM),
    'post_date' => optional_param('post_date', null, PARAM_TEXT),
    'tran_id' => optional_param('tran_id', null, PARAM_ALPHANUM),
    'ref' => optional_param('ref', null, PARAM_ALPHANUM),
    'track_id' => optional_param('track_id', null, PARAM_ALPHANUM),
    'auth' => optional_param('auth', null, PARAM_ALPHANUM),
    'order_id' => optional_param('order_id', null, PARAM_ALPHANUM),
    'requested_order_id' => optional_param('requested_order_id', null, PARAM_ALPHANUM),
    'refund_order_id' => optional_param('refund_order_id', null, PARAM_ALPHANUM),
    'payment_type' => optional_param('payment_type', null, PARAM_ALPHANUM),
    'invoice_id' => optional_param('invoice_id', null, PARAM_ALPHANUM),
    'transaction_date' => optional_param('transaction_date', null, PARAM_TEXT),
    'receipt_id' => optional_param('receipt_id', null, PARAM_ALPHANUM),
    'trn_udf' => optional_param('trn_udf', null, PARAM_TEXT),
];

require_sesskey();
require_login();

$instance = $DB->get_record('enrol', array('id' => $instanceid, 'enrol' => 'upayment'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $instance->courseid), '*', MUST_EXIST);
$context = context_course::instance($course->id, MUST_EXIST);

$PAGE->set_url('/enrol/upayment/processing.php', array('instanceid' => $instanceid));
$PAGE->set_context($context);
$PAGE->set_title(get_string('processing_payment', 'enrol_upayment'));
$PAGE->set_heading(get_string('processing_payment', 'enrol_upayment'));

// Add CSS for the loading animation
$PAGE->requires->css(new moodle_url('/enrol/upayment/styles.css'));

echo $OUTPUT->header();

// Display the loading animation and message
?>
<div class="payment-processing-container">
    <div class="payment-processing-spinner"></div>
    <div class="payment-processing-message"><?php echo get_string('payment_processing', 'enrol_upayment'); ?></div>
    <?php if ($status === 'error' || $status === 'cancelled' || $status === 'timeout'): ?>
        <div class="payment-processing-actions">
            <a href="<?php echo new moodle_url('/enrol/upayment/pay.php', array('instanceid' => $instanceid, 'sesskey' => sesskey())); ?>" 
               class="btn btn-primary">
                <?php echo get_string('try_again', 'enrol_upayment'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<?php

// Handle payment verification and enrolment if UPayments parameters are present
if (!empty($upayments_params['track_id']) && !empty($upayments_params['order_id'])) {
    // Verify payment with UPayments API
    if (enrol_upayment_verify_payment($upayments_params['track_id'])) {
        // Check if payment was already processed
        $existing = $DB->get_record('enrol_upayment_transactions', array('track_id' => $upayments_params['track_id']));
        if (!$existing) {
            // Log transaction
            // Ensure amount and currency are set in params for logging if not provided by gateway
            $upayments_params['amount'] = $instance->cost;
            $upayments_params['currency'] = $instance->currency;
            enrol_upayment_log_transaction($USER->id, $instance->id, $upayments_params);

            // Enrol user
            $plugin = enrol_get_plugin('upayment');
            $plugin->enrol_user_via_callback($instance->id, $USER->id, $upayments_params['track_id'], $instance->cost);
        }
        $status = 'success';
        $message = get_string('payment_success', 'enrol_upayment');
    } else {
        $status = 'error'; // Verification failed
        $message = get_string('payment_error', 'enrol_upayment');
    }
} else {
    // If no UPayments parameters, display the initial processing message
    $message = get_string('payment_processing', 'enrol_upayment');
}

// Update page title based on status
$PAGE->set_title(get_string('payment_' . $status, 'enrol_upayment'));
$PAGE->set_heading(get_string('payment_' . $status, 'enrol_upayment'));

// Auto-redirect after a delay for success or redirect immediately for errors/cancellations
if ($status === 'success') {
    // Redirect to course page after 3 seconds
    $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
    echo html_writer::script("setTimeout(function() { window.location.href = \'" . $courseurl . "\'; }, 3000);");
} else if ($status === 'error' || $status === 'cancelled' || $status === 'timeout') {
    // Redirect back to course page with notification
    $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
    $notificationtype = \core\output\notification::NOTIFY_ERROR; // Or NOTIFY_WARNING for cancelled/timeout if preferred
    // You might want a more specific error message here based on the actual issue
    $errormessage = get_string('payment_' . $status, 'enrol_upayment');
    redirect($returnurl, $errormessage, null, $notificationtype);
}

echo $OUTPUT->footer(); 