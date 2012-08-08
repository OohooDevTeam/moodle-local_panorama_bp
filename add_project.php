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
 * This file is used for adding, displaying, and editing projects in this 
 * plugin. It will typically be accessed by clicking on the "Add Project" button 
 * on the main page or by clicking on an existing projct on that same page. 
 * 
 * This page handles the creation of the form and the processing of data that is
 * submitted by that form.
 */

//    -----    Includes    -----    //
require_once(dirname(dirname(dirname((__FILE__)))) . '/config.php');
require_once(dirname(__FILE__) . '/add_project_form.php');


//    -----    Security    -----    //
require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('local/panorama_business_process:edit', $context);
//    -----    Rendering Info    -----    //
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . "/local/panorama_bp/add_project.php");
$PAGE->set_title(get_string('pluginname', 'local_panorama_bp'));
$PAGE->set_heading(get_string('pluginname', 'local_panorama_bp'));

$PAGE->requires->css(new moodle_url('styles.css'));
$mform = new add_prj_form();

if ($mform->is_cancelled()) {
    header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/view.php');
} else if ($data = $mform->get_data()) {
    process($data);
    header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/view.php');
} else {
    
    //Ouput the header.
    echo $OUTPUT->header();

    $mform->display();

    echo $OUTPUT->footer();
}

function process($data) {
    global $DB;
    unset($data->submitbutton);
    
    if (isset($_REQUEST['id'])) {
        $data->id = $_REQUEST['id'];
        $DB->update_record('panorama_bp', $data);
    } else {
        $DB->insert_record('panorama_bp', $data);
    }
}

?>
