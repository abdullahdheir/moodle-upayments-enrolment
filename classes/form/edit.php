<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class enrol_upayment_edit_form extends moodleform {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_upayment'));

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_upayment'), array('size' => 8));
        $mform->setType('cost', PARAM_FLOAT);
        $mform->addRule('cost', null, 'required', null, 'client');

        $currencies = [
            'KWD' => 'KWD',
            'USD' => 'USD',
            'EUR' => 'EUR',
            'SAR' => 'SAR',
            'AED' => 'AED',
            'GBP' => 'GBP',
            // Add more as needed
        ];
        $mform->addElement('select', 'currency', get_string('currency', 'enrol_upayment'), $currencies);
        $mform->setDefault('currency', 'KWD');

        $this->add_action_buttons(true, get_string('addmethod', 'enrol'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if ($data['cost'] < 0) {
            $errors['cost'] = get_string('costerror', 'enrol_upayment');
        }
        return $errors;
    }
} 