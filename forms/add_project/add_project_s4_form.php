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
