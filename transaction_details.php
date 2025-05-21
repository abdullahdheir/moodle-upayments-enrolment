<?php
require_once('../../config.php');
require_once('lib.php');

require_login();
require_capability('enrol/upayment:viewtransactions', context_system::instance());

$id = required_param('id', PARAM_INT);

$transaction = $DB->get_record('enrol_upayment_transactions', ['id' => $id], '*', MUST_EXIST);
$user = $DB->get_record('user', ['id' => $transaction->userid], '*', MUST_EXIST);
$instance = $DB->get_record('enrol', ['id' => $transaction->instanceid], '*', MUST_EXIST);
$course = $DB->get_record('course', ['id' => $instance->courseid], '*', MUST_EXIST);

$PAGE->set_url('/enrol/upayment/transaction_details.php', ['id' => $id]);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('transaction_details', 'enrol_upayment'));
$PAGE->set_heading(get_string('transaction_details', 'enrol_upayment'));
$PAGE->set_pagelayout('admin');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('transaction_details', 'enrol_upayment'));

// Display transaction details
$table = new html_table();
$table->attributes['class'] = 'generaltable';

// Basic information
$table->data[] = [
    get_string('transactionid', 'enrol_upayment'),
    $transaction->id
];
$table->data[] = [
    get_string('user'),
    fullname($user) . ' (' . $user->email . ')'
];
$table->data[] = [
    get_string('course'),
    $course->fullname
];
$table->data[] = [
    get_string('amount'),
    format_float($transaction->amount, 2) . ' ' . $transaction->currency
];
$table->data[] = [
    get_string('status'),
    get_string('transaction_' . $transaction->status, 'enrol_upayment')
];
$table->data[] = [
    get_string('timecreated'),
    userdate($transaction->timecreated)
];

// UPayments specific information
$upayment_fields = [
    'payment_id' => get_string('payment_id', 'enrol_upayment'),
    'result' => get_string('result', 'enrol_upayment'),
    'post_date' => get_string('post_date', 'enrol_upayment'),
    'tran_id' => get_string('tran_id', 'enrol_upayment'),
    'ref' => get_string('ref', 'enrol_upayment'),
    'track_id' => get_string('track_id', 'enrol_upayment'),
    'auth' => get_string('auth', 'enrol_upayment'),
    'order_id' => get_string('order_id', 'enrol_upayment'),
    'requested_order_id' => get_string('requested_order_id', 'enrol_upayment'),
    'refund_order_id' => get_string('refund_order_id', 'enrol_upayment'),
    'payment_type' => get_string('payment_type', 'enrol_upayment'),
    'invoice_id' => get_string('invoice_id', 'enrol_upayment'),
    'transaction_date' => get_string('transaction_date', 'enrol_upayment'),
    'receipt_id' => get_string('receipt_id', 'enrol_upayment'),
    'trn_udf' => get_string('trn_udf', 'enrol_upayment')
];

foreach ($upayment_fields as $field => $label) {
    if (!empty($transaction->$field)) {
        $table->data[] = [$label, $transaction->$field];
    }
}

echo html_writer::table($table);

// Add back button
echo $OUTPUT->single_button(
    new moodle_url('/enrol/upayment/transactions.php'),
    get_string('back_to_transactions', 'enrol_upayment'),
    'get'
);

echo $OUTPUT->footer(); 