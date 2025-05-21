<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class enrol_upayment_edit_form extends moodleform {
    protected $instance;
    protected $course;

    public function definition() {
        global $DB;

        $mform = $this->_form;
        list($this->instance, $this->course) = $this->_customdata;
        $this->instance = (object)$this->instance;

        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_upayment'));

        $roles = get_default_enrol_roles($this->context, $this->instance->roleid);
        $mform->addElement('select', 'roleid', get_string('role', 'enrol_upayment'), $roles);

        // Get current teacher cost if exists
        $teacher_cost = $DB->get_field('enrol_upayment_costs', 'cost', 
            ['courseid' => $this->course->id, 'userid' => $USER->id]);

        $mform->addElement('text', 'teacher_cost', get_string('teacher_cost', 'enrol_upayment'), 
            array('size' => 8));
        $mform->setType('teacher_cost', PARAM_FLOAT);
        $mform->setDefault('teacher_cost', $teacher_cost ?: $this->instance->cost);
        $mform->addHelpButton('teacher_cost', 'teacher_cost', 'enrol_upayment');

        $this->add_action_buttons(true, get_string('savechanges'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        
        if ($data['teacher_cost'] < 0) {
            $errors['teacher_cost'] = get_string('error_cost_negative', 'enrol_upayment');
        }
        
        return $errors;
    }
} 