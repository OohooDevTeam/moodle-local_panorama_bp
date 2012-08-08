<?php

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

$PAGE->requires->css(new moodle_url('styles.css'));
//Ouput the header.
$mform = new task_form();

if ($data = $mform->get_data()) {
    process($data);
    if (property_exists($data, 'save_rtrn')) {
        header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/add_project.php?bpid=' . $bpid);
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
    header('Location: ' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=' . $data->phase .'&bpid=' . $data->bp_id);
}
?>

