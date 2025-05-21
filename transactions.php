<?php
require_once('../../config.php');
require_once($CFG->libdir.'/tablelib.php');
require_once('lib.php');

require_login();
require_capability('enrol/upayment:viewtransactions', context_system::instance());

$PAGE->set_url('/enrol/upayment/transactions.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('transactions', 'enrol_upayment'));
$PAGE->set_heading(get_string('transactions', 'enrol_upayment'));
$PAGE->set_pagelayout('admin');

// Get filter parameters
$status = optional_param('status', '', PARAM_ALPHANUM);
$datefrom = optional_param('datefrom', '', PARAM_ALPHANUM);
$dateto = optional_param('dateto', '', PARAM_ALPHANUM);
$search = optional_param('search', '', PARAM_TEXT);

// Create the table
$table = new flexible_table('upayment-transactions');
$table->define_columns(['id', 'user', 'course', 'amount', 'status', 'payment_id', 'track_id', 'transaction_date', 'actions']);
$table->define_headers([
    get_string('transactionid', 'enrol_upayment'),
    get_string('user'),
    get_string('course'),
    get_string('amount'),
    get_string('status'),
    get_string('payment_id', 'enrol_upayment'),
    get_string('track_id', 'enrol_upayment'),
    get_string('transaction_date', 'enrol_upayment'),
    get_string('actions')
]);

$table->set_attribute('class', 'generaltable generalbox');
$table->setup();

// Build the SQL query
$sql = "SELECT t.*, u.firstname, u.lastname, c.fullname as coursename 
        FROM {enrol_upayment_transactions} t 
        JOIN {user} u ON t.userid = u.id 
        JOIN {enrol} e ON t.instanceid = e.id 
        JOIN {course} c ON e.courseid = c.id 
        WHERE 1=1";
$params = [];

if ($status) {
    $sql .= " AND t.status = ?";
    $params[] = $status;
}

if ($datefrom) {
    $sql .= " AND t.timecreated >= ?";
    $params[] = strtotime($datefrom);
}

if ($dateto) {
    $sql .= " AND t.timecreated <= ?";
    $params[] = strtotime($dateto);
}

if ($search) {
    $sql .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR t.payment_id LIKE ? OR t.track_id LIKE ?)";
    $searchparam = "%$search%";
    $params = array_merge($params, [$searchparam, $searchparam, $searchparam, $searchparam]);
}

$sql .= " ORDER BY t.timecreated DESC";

// Get the total count
$total = $DB->count_records_sql("SELECT COUNT(*) FROM ($sql) t", $params);

// Set up pagination
$perpage = 20;
$page = optional_param('page', 0, PARAM_INT);
$table->pagesize($perpage, $total);

// Get the records
$records = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

// Display the filter form
echo $OUTPUT->header();
echo $OUTPUT->box_start('generalbox');

$filterform = new moodle_url($PAGE->url, ['status' => $status, 'datefrom' => $datefrom, 'dateto' => $dateto, 'search' => $search]);
echo '<form method="get" action="' . $filterform . '" class="mform">';
echo '<div class="fitem">';
echo '<label>' . get_string('status') . '</label>';
echo '<select name="status">';
echo '<option value="">' . get_string('all') . '</option>';
echo '<option value="success"' . ($status === 'success' ? ' selected' : '') . '>' . get_string('transaction_success', 'enrol_upayment') . '</option>';
echo '<option value="failed"' . ($status === 'failed' ? ' selected' : '') . '>' . get_string('transaction_failed', 'enrol_upayment') . '</option>';
echo '</select>';
echo '</div>';

echo '<div class="fitem">';
echo '<label>' . get_string('datefrom', 'enrol_upayment') . '</label>';
echo '<input type="date" name="datefrom" value="' . $datefrom . '">';
echo '</div>';

echo '<div class="fitem">';
echo '<label>' . get_string('dateto', 'enrol_upayment') . '</label>';
echo '<input type="date" name="dateto" value="' . $dateto . '">';
echo '</div>';

echo '<div class="fitem">';
echo '<label>' . get_string('search') . '</label>';
echo '<input type="text" name="search" value="' . s($search) . '">';
echo '</div>';

echo '<div class="fitem">';
echo '<input type="submit" value="' . get_string('filter') . '" class="btn btn-primary">';
echo '</div>';
echo '</form>';

echo $OUTPUT->box_end();

// Display the table
foreach ($records as $record) {
    $row = [];
    $row[] = $record->id;
    $row[] = fullname($record);
    $row[] = $record->coursename;
    $row[] = format_float($record->amount, 2) . ' ' . $record->currency;
    $row[] = get_string('transaction_' . $record->status, 'enrol_upayment');
    $row[] = $record->payment_id;
    $row[] = $record->track_id;
    $row[] = userdate($record->timecreated);
    
    // Add action buttons
    $actions = '';
    $actions .= $OUTPUT->action_icon(
        new moodle_url('/enrol/upayment/transaction_details.php', ['id' => $record->id]),
        new pix_icon('i/info', get_string('view'))
    );
    $row[] = $actions;
    
    $table->add_data($row);
}

$table->print_html();

echo $OUTPUT->footer(); 