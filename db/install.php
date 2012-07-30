<?php
function xmldb_local_panorama_bp_install (){
    global $CFG, $DB;
    
    ///Add default configuration

    $bp_config = new stdClass();
    $bp_config->sugarcrm_url = null;
    $bp_config->sugarcrm_username = 'admin';
    $bp_config->sugarcrm_pwd = null;

    $DB->insert_record('panorama_bp_config', $bp_config);
}
?>
