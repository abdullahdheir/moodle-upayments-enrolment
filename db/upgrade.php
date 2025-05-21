<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_enrol_upayment_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2025052107) {
        // Define table enrol_upayment_transactions to be created.
        $table = new xmldb_table('enrol_upayment_transactions');
        if (!$dbman->table_exists($table)) {
            // Create the table if it doesn't exist
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2025052107, 'enrol', 'upayment');
    }

    return true;
} 