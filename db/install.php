<?php
/**
 * ************************************************************************
 * *                     Panorama Buisness Process                       **
 * ************************************************************************
 * @package     local                                                    **
 * @subpackage  Panorama Buisness Process                                **
 * @name        Panorama Buisness Process                                **
 * @copyright   oohoo.biz                                                **
 * @link        http://oohoo.biz                                         **
 * @author      Andrew McCann                                            **
 * @license     Copyright                                                **
 * ************************************************************************
 * ********************************************************************** */

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
