<?php

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
