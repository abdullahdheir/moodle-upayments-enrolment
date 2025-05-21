<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_enroll_upayment_install() {
    global $DB;
    // Nothing here; optional table can be added later.

    // Create transaction log table
    $dbman = $DB->get_manager();
    $table = new xmldb_table('enroll_upayment_transactions');

    $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
    $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('instanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('amount', XMLDB_TYPE_NUMBER, '10,2', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('currency', XMLDB_TYPE_CHAR, '3', null, XMLDB_NOTNULL, null, 'KWD');
    $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('status', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, 'pending');
    $table->add_field('is_sandbox', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
    $table->add_field('payment_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('result', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('post_date', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('tran_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('ref', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('track_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('auth', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('order_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('requested_order_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('refund_order_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('payment_type', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('invoice_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('transaction_date', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('receipt_id', XMLDB_TYPE_CHAR, '255', null, null, null, null);
    $table->add_field('trn_udf', XMLDB_TYPE_CHAR, '255', null, null, null, null);

    $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
    $table->add_key('userid', XMLDB_KEY_FOREIGN, array('userid'), 'user', array('id'));
    $table->add_key('instanceid', XMLDB_KEY_FOREIGN, array('instanceid'), 'enrol', array('id'));

    $table->add_index('status', XMLDB_INDEX_NOTUNIQUE, array('status'));
    $table->add_index('timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));
    $table->add_index('track_id', XMLDB_INDEX_NOTUNIQUE, array('track_id'));
    $table->add_index('payment_id', XMLDB_INDEX_NOTUNIQUE, array('payment_id'));
    $table->add_index('is_sandbox', XMLDB_INDEX_NOTUNIQUE, array('is_sandbox'));

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }

    // Create teacher costs table
    $table = new xmldb_table('enroll_upayment_costs');
    if (!$dbman->table_exists($table)) {
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cost', XMLDB_TYPE_NUMBER, '10,2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_index('courseuser', XMLDB_INDEX_UNIQUE, ['courseid', 'userid']);
        $dbman->create_table($table);
    }

    return true;
}
