<?php
//    -----    Includes    -----    //
require_once(dirname(dirname(dirname(dirname((__FILE__))))) . '/config.php');

//    -----    Security    -----    //
require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('local/panorama_business_process:edit', $context);

//    -----    Rendering Info    -----    //
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot."/local/panorama_bp/view.php");
$PAGE->set_title(get_string('pluginname', 'local_panorama_bp'));
$PAGE->set_heading(get_string('pluginname', 'local_panorama_bp'));

require_once('settings_form.php');
    
    $mform = new local_panorama_bp_config_form();
    //If user pressed the cancel button
    if ($mform->is_cancelled()) {
        redirect("$CFG->wwwroot/local/panorama_bp/view.php", get_string('cancelled','local_panorama_bp'), 1);
    } else if ($fromform=$mform->get_data()) {
            $bp_config = new stdClass();
           
            $bp_config->id = $fromform->id;
            $bp_config->sugarcrm_url = $fromform->sugarcrm_url;
            $bp_config->sugarcrm_username = $fromform->sugarcrm_username;
            $bp_config->sugarcrm_pwd = $fromform->sugarcrm_pwd;
           
            
                $config = $DB->update_record('panorama_bp_config', $bp_config, true);
            
            redirect("$CFG->wwwroot/local/panorama_bp/view.php", get_string('config_saved','local_panorama_bp'), 1);
    } else {
        echo $OUTPUT->header();
        
        $bp_config = $DB->get_record('panorama_bp_config',array('id' => 1));
            $toform['id'] =  1;
            $toform['sugarcrm_url'] =  $bp_config->sugarcrm_url;
            $toform['sugarcrm_username'] =  $bp_config->sugarcrm_username;
            $toform['sugarcrm_pwd'] =  $bp_config->sugarcrm_pwd;
        
       
        $mform->set_data($toform);
        $mform->display();
        echo $OUTPUT->footer();
    }
?>
