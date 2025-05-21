<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('enrolsettingsupayment', get_string('pluginname', 'enrol_upayment'));
    $ADMIN->add('enrolments', $settings);

    // General settings
    $settings->add(new admin_setting_heading('enrol_upayment_settings', '', get_string('pluginname_desc', 'enrol_upayment')));

    // Production API Configuration
    $settings->add(new admin_setting_heading('enrol_upayment_prod_settings', 
        get_string('prod_settings', 'enrol_upayment'), 
        get_string('prod_settings_desc', 'enrol_upayment')));

    $settings->add(new admin_setting_configtext('enrol_upayment/token', 
        get_string('token', 'enrol_upayment'),
        get_string('token_desc', 'enrol_upayment'), 
        '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('enrol_upayment/apiurl',
        get_string('apiurl', 'enrol_upayment'),
        get_string('apiurl_desc', 'enrol_upayment'),
        'https://uapi.upayments.com/api/v1/', PARAM_URL));

    // Sandbox settings
    $settings->add(new admin_setting_heading('enrol_upayment_sandbox_settings',
        get_string('sandbox_settings', 'enrol_upayment'),
        get_string('sandbox_settings_desc', 'enrol_upayment')));

    $settings->add(new admin_setting_configcheckbox('enrol_upayment/sandbox',
        get_string('sandbox', 'enrol_upayment'),
        get_string('sandbox_desc', 'enrol_upayment'),
        0));

    $settings->add(new admin_setting_configtext('enrol_upayment/sandbox_token',
        get_string('sandbox_token', 'enrol_upayment'),
        get_string('sandbox_token_desc', 'enrol_upayment'),
        '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('enrol_upayment/sandbox_apiurl',
        get_string('sandbox_apiurl', 'enrol_upayment'),
        get_string('sandbox_apiurl_desc', 'enrol_upayment'),
        'https://sandboxapi.upayments.com/api/v1/', PARAM_URL));

    // Transaction Log
    $settings->add(new admin_setting_heading('enrol_upayment_transactions', 
        get_string('transactions', 'enrol_upayment'),
        html_writer::link(new moodle_url('/enrol/upayment/transactions.php'),
            get_string('view_transactions', 'enrol_upayment'),
            ['class' => 'btn btn-primary']
        )
    ));
}
