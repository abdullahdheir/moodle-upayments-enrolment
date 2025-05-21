<?php
namespace enrol_upayment\form;

defined('MOODLE_INTERNAL') || die();

$id = required_param('id', PARAM_INT);

require_once($CFG->libdir.'/formslib.php');

class edit extends \moodleform {
    public function definition() {
        $instance = $DB->get_record('enrol', array('id' => $id, 'enrol' => 'upayment'), '*', MUST_EXIST);

        $mform = $this->_form;
        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_upayment'));

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_upayment'), array('size' => 8));
        $mform->setType('cost', PARAM_FLOAT);
        $mform->addRule('cost', null, 'required', null, 'client');
        $mform->addHelpButton('cost', 'cost', 'enrol_upayment');

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
        $mform->addHelpButton('currency', 'currency', 'enrol_upayment');

        // Set default values for editing
        if (isset($instance->id)) {
            $mform->setDefault('cost', $instance->cost);
            $mform->setDefault('currency', $instance->currency);
        }

        $this->add_action_buttons(true, get_string('updatemethod', 'enrol'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if ($data['cost'] < 0) {
            $errors['cost'] = get_string('costerror', 'enrol_upayment');
        }
        return $errors;
    }
} 