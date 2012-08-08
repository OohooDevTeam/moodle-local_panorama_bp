<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is the page that lets you display, add, and edit tasks within a given 
 * phase. It will typically be accessed by clicking on a "Phase [1-6]" button or
 * by clicking on a task id so that you can edit that specific task.
 * 
 * This page handles the creation of the form and the processing of data that is
 * submitted by that form.
 */
//    -----    Includes    -----    //
require_once(dirname(dirname(dirname((__FILE__)))) . '/config.php');
require_once(dirname(__FILE__) . '/task_form.php');


//    -----    Security    -----    //
require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('local/panorama_business_process:edit', $context);
$bpid = required_param('bpid', PARAM_INT);

//    -----    Rendering Info    -----    //
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . "/local/panorama_bp/tasks.php");
$PAGE->set_title(get_string('pluginname', 'local_panorama_bp'));
$PAGE->set_heading(get_string('pluginname', 'local_panorama_bp'));



//    -----    Navigation    -----    //
$PAGE->navbar->add(get_string('panorama_bp', 'local_panorama_bp'),
        '/local/panorama_bp/view.php');
$bpid = required_param('bpid', PARAM_INT);
$PAGE->navbar->add(get_string('add_project', 'local_panorama_bp'),
        '/local/panorama_bp/add_project.php?bpid=' . $bpid);
$PAGE->navbar->add(get_string('phase', 'local_panorama_bp'));


$PAGE->requires->css(new moodle_url('styles.css'));
//Ouput the header.
$mform = new task_form();

if ($data = $mform->get_data()) {
    if (property_exists($data, 'rtrn')) {
        header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/add_project.php?bpid=' . $bpid);
    } else {
        process($data);
        if (property_exists($data, 'save_rtrn')) {
            header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/add_project.php?bpid=' . $bpid);
        }
    }
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();

function process($data) {
    global $DB, $CFG;
    unset($data->submitbutton);

    if (isset($_REQUEST['id'])) {
        $data->id = $_REQUEST['id'];
        $DB->update_record('panorama_bp_phases', $data);
    } else {
        $DB->insert_record('panorama_bp_phases', $data);
    }
    header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=' . $data->phase . '&bpid=' . $data->bp_id);
}
?>

