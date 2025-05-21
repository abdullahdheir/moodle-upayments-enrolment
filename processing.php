<?php
require_once('../../config.php');
require_once($CFG->libdir.'/enrollib.php');
require_once('lib.php');

$instanceid = required_param('instanceid', PARAM_INT);
$sesskey = required_param('sesskey', PARAM_ALPHANUM);
$status = optional_param('status', 'processing', PARAM_ALPHANUM);

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

// Get the appropriate message based on status
$message = '';
switch ($status) {
    case 'processing':
        $message = get_string('payment_processing', 'enrol_upayment');
        break;
    case 'verifying':
        $message = get_string('payment_verification', 'enrol_upayment');
        break;
    case 'success':
        $message = get_string('payment_success', 'enrol_upayment');
        break;
    case 'error':
        $message = get_string('payment_error', 'enrol_upayment');
        break;
    case 'cancelled':
        $message = get_string('payment_cancelled', 'enrol_upayment');
        break;
    case 'timeout':
        $message = get_string('payment_timeout', 'enrol_upayment');
        break;
    default:
        $message = get_string('payment_processing', 'enrol_upayment');
}

// Display the loading animation and message
?>
<div class="payment-processing-container">
    <div class="payment-processing-spinner"></div>
    <div class="payment-processing-message"><?php echo $message; ?></div>
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
echo $OUTPUT->footer(); 