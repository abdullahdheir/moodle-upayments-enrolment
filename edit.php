<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$courseid = required_param('courseid', PARAM_INT);
$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$instance = $DB->get_record('enrol', array('id' => $id, 'courseid' => $course->id, 'enrol' => 'upayment'), '*', MUST_EXIST);

$context = context_course::instance($course->id, MUST_EXIST);
require_login($course);
require_capability('enrol/upayment:config', $context);

$PAGE->set_url('/enrol/upayment/edit.php', array('courseid' => $course->id, 'id' => $instance->id));
$PAGE->set_pagelayout('admin');

$return = new moodle_url('/enrol/instances.php', array('id' => $course->id));
if (!enrol_is_enabled('upayment')) {
    redirect($return);
}

$mform = new enrol_upayment_edit_form(null, array($instance, $course));

if ($mform->is_cancelled()) {
    redirect($return);
} else if ($data = $mform->get_data()) {
    if ($data->roleid != $instance->roleid) {
        $instance->roleid = $data->roleid;
        $DB->update_record('enrol', $instance);
    }
    
    // Update teacher cost
    if (isset($data->teacher_cost)) {
        $cost_record = $DB->get_record('enrol_upayment_costs', 
            ['courseid' => $course->id, 'userid' => $USER->id]);
            
        if ($cost_record) {
            $cost_record->cost = $data->teacher_cost;
            $cost_record->timemodified = time();
            $DB->update_record('enrol_upayment_costs', $cost_record);
        } else {
            $DB->insert_record('enrol_upayment_costs', [
                'courseid' => $course->id,
                'userid' => $USER->id,
                'cost' => $data->teacher_cost,
                'timecreated' => time(),
                'timemodified' => time()
            ]);
        }
    }
    
    redirect($return);
}

$PAGE->set_title(get_string('pluginname', 'enrol_upayment'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'enrol_upayment'));
$mform->display();
echo $OUTPUT->footer(); 