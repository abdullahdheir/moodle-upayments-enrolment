<?php
require('../../config.php');
$trackid    = required_param('TrackId', PARAM_TEXT);
$instanceid = required_param('instanceid', PARAM_INT);
$userid     = required_param('userid', PARAM_INT);
$plugin = enrol_get_plugin('upayment');
if ($plugin->process_callback($trackid,$instanceid,$userid)){
    redirect(new moodle_url('/course/view.php',['id'=>get_course($instanceid)->courseid]),
             get_string('thanks','enrol_upayment'),5);
} else {
    echo get_string('callbackerror','enrol_upayment');
}
