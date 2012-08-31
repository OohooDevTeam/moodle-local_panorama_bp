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

/**
 * This is the what is typically displayed when you visit add_project.php. This 
 * form is what acctually displays and allows you to edit/add a project.
 * 
 * Note: The functions in this file are organized. Please keep them that way.
 */
require_once("$CFG->libdir/formslib.php");
require_once("lib.php");

class add_prj_form extends moodleform {

    const GENERAL_STAGE = 0;
    const QUOTE_STAGE = 1;
    const SERVICE_AGREEMENT_STAGE = 2;
    const FINALIZATION_STAGE = 3;

    private $bpid;
    private $stage;

    function definition() {
        global $DB, $PAGE;
        $PAGE->navbar->add(get_string('stage_1', 'local_panorama_bp'));

        //Load plugin config info.
        load_bp_config();

        //Add the "General" Section.
        $this->addGeneralSection();
        
        //Add the "Client Information" Section.
        $this->addClientSection();
        
        //Add the "Software Versions" Section.
        $this->addSoftwareSection();
        
        //Load any previously saved data.
        $this->loadData();
    }

    private function addGeneralSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('general', 'local_panorama_bp'));

        //Get a list of all SugarCRM contacts.
        $contacts = sugarCRM_contacts();

        $contact_select_array = array(get_string('none', 'local_panorama_bp'));

        foreach ($contacts as $contact) {
            $contact_select_array[$contact->id] = $contact->name;
        }
    }

    private function addClientSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('client_info', 'local_panorama_bp'));
    }

    private function addSoftwareSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('software_versions', 'local_panorama_bp'));
    }

    function loadData() {
        
    }

}

?>
