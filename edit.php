<?php
require_once('../../config.php');
require_once('lib.php');
require_once($CFG->libdir.'/enrollib.php');

$courseid = optional_param('courseid', PARAM_INT);

if(!$courseid){
   redirect($CFG->wwwroot . '/enrol/instances.php');
}

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

$mform = new \enrol_upayment\form\edit(null, array($instance, $course));

if ($mform->is_cancelled()) {
    redirect($return);
} else if ($data = $mform->get_data()) {
    // Update instance fields
    if (isset($data->cost)) {
        $instance->cost = $data->cost;
        $DB->update_record('enrol', $instance);
    }
    if (isset($data->currency)) {
        $instance->currency = $data->currency;
        $DB->update_record('enrol', $instance);
    }
    redirect($return);
}

$PAGE->set_title(get_string('pluginname', 'enrol_upayment'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer(); 