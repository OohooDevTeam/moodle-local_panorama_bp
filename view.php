<?php

//    -----    Includes    -----    //
require_once(dirname(dirname(dirname((__FILE__)))) . '/config.php');
include_once('lib.php');


//    -----    Security    -----    //
require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('local/panorama_business_process:edit', $context);

load_bp_config();

//    -----    Rendering Info    -----    //
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot."/local/panorama_bp/view.php");
$PAGE->set_title(get_string('pluginname', 'local_panorama_bp'));
$PAGE->set_heading(get_string('pluginname', 'local_panorama_bp'));
//Ouput the header.
echo $OUTPUT->header();

//    -----    MAIN CONTENT    -----    //

sugarCRM_contacts();


//    -----    END OF MAIN CONTENT    -----    //
//Output the footer
echo $OUTPUT->footer();
?>
