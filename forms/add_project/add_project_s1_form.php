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
require_once("$CFG->dirroot/local/panorama_bp/lib.php");
require_once("$CFG->dirroot/local/panorama_bp/forms/moodleform_eXtreme.php");

class add_prj_form extends moodleform_exTreme {

    private $bpid;

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
        $this->add_action_buttons();
    }

    /**
     * A section to enter general information about the project and the company.
     */
    private function addGeneralSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('general', 'local_panorama_bp'));

        //Custom format makes the input bozes follow my css rules.
        $this->_form->addElement('html', '<span class="custom_format">');

        $mform->addElement('text', 'project_name',
                get_string('project_name', 'local_panorama_bp'));

        $mform->addElement('text', 'company_name',
                get_string('company', 'local_panorama_bp'));

        $mform->addElement('text', 'company_name',
                get_string('preferred_language', 'local_panorama_bp'));

        $this->_form->addElement('html', '</span>'); //Close custom_format
    }

    /**
     * A section to add client contact information.
     */
    private function addClientSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('client_info', 'local_panorama_bp'));
        //Custom format makes the input bozes follow my css rules.
        $this->_form->addElement('html', '<span class="custom_format">');

        //Get a list of all SugarCRM contacts.
        $contacts = sugarCRM_contacts();

        $grid = $this->getContactGrid($contacts);

        $this->addGrid($grid, '700px');

        //If the selector is not set to index 0. (Not Used) then disable the client name, email and phone fields.
        $mform->disabledIf('client_name', 'client_sugarid', 'neq', 0);
        $mform->disabledIf('client_email', 'client_sugarid', 'neq', 0);
        $mform->disabledIf('client_work_phone', 'client_sugarid', 'neq', 0);
        $mform->disabledIf('it_name', 'it_sugarid', 'neq', 0);
        $mform->disabledIf('it_email', 'it_sugarid', 'ne1', 0);
        $mform->disabledIf('it_work_phone', 'it_sugarid', 'ne1', 0);

        $this->_form->addElement('html', '</span>'); //Close custom_format
    }

    /**
     * Creates a input field grid with fields to fill in IT and Client contact 
     * information.
     * 
     * @param $contacts A list of all contacts in sugarCRM
     * @return array    A two dimensional array representing the layout of the
     *                  contact field grid. This will be passed into $this->addGrid
     */
    private function getContactGrid($contacts) {
        $mform = $this->_form;
        //Write the script that fills out information if a contact 
        //is selected. Start by passing all the contact info into
        //the script.
        $contact_list = json_encode($contacts);

        $crmSelectorScript = <<<SCRIPT
                        var contact_list = $contact_list;

                        //Subtract 1 because we added in the "None" index as 0.
                        var index = this.selectedIndex - 1;
                        var type = this.getAttribute('contacttype');
                        
                        //This will occur if none "None" was selected.
                        if(index != -1 ) {
                            var contact = contact_list[index];
                            
                            var client_name = document.getElementById('id_' + type + '_name');
                            client_name.value = contact.name;
                            
                            var client_email = document.getElementById('id_' + type + '_email');
                            client_email.value = contact.email;
                            
                            var client_phone = document.getElementById('id_' + type + '_work_phone');
                            client_phone.value = contact.phone;
                        } else {
                        
                            var client_name = document.getElementById('id_' + type + '_name');
                            client_name.value = '';
                            
                            var client_email = document.getElementById('id_' + type + '_email');
                            client_email.value = '';
                            
                            var client_phone = document.getElementById('id_' + type + '_work_phone');
                            client_phone.value = '';
                        }
                        
SCRIPT;

        //Create the selection array.
        $contact_select_array = array(get_string('none', 'local_panorama_bp'));

        foreach ($contacts as $contact) {
            $contact_select_array[$contact->id] = $contact->name;
        }

        //Now build the grid.
        $grid = array(
            //First Row
            array(
                $mform->createElement('html',
                        '<strong>' . get_string('client_contact',
                                'local_panorama_bp') . '</strong>'),
                $mform->createElement('html',
                        '<strong>' . get_string('it_contact',
                                'local_panorama_bp') . '</strong>'),
            ),
            //Second Row (CRM Contact Selector)
            array(
                $mform->createElement('select', 'client_sugarid',
                        get_string('crmcontact', 'local_panorama_bp'),
                        $contact_select_array,
                        array(
                    'onchange' => $crmSelectorScript,
                    'contacttype' => 'client'
                        )
                ),
                $mform->createElement('select', 'it_sugarid',
                        get_string('crmcontact', 'local_panorama_bp'),
                        $contact_select_array,
                        array(
                    'onchange' => $crmSelectorScript,
                    'contacttype' => 'it'
                        )
                )
            ),
            //Third row (Names)
            array(
                $mform->createElement('text', 'client_name',
                        get_string('client_name', 'local_panorama_bp')),
                $mform->createElement('text', 'it_name',
                        get_string('it_name', 'local_panorama_bp'))
            ),
            //Fourth row (Emails)
            array(
                $mform->createElement('text', 'client_email',
                        get_string('client_email', 'local_panorama_bp')),
                $mform->createElement('text', 'it_email',
                        get_string('it_email', 'local_panorama_bp'))
            ),
            //Fifth/Last Row (Phone Numbers)
            array(
                $mform->createElement('text', 'client_work_phone',
                        get_string('client_phone', 'local_panorama_bp')),
                $mform->createElement('text', 'it_work_phone',
                        get_string('it_phone', 'local_panorama_bp'))
            )
        );

        return $grid;
    }

    /**
     * Section to enter information about software versions that the client
     * uses.
     */
    private function addSoftwareSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('software_versions', 'local_panorama_bp'));
        
        //Custom format makes the input bozes follow my css rules.
        $this->_form->addElement('html', '<span class="custom_format">');
        
        $mform->addElement('text', 'client_moodle_version',
                get_string('moodle', 'local_panorama_bp'));

        $mform->addElement('text', 'client_browsers',
                get_string('browsers', 'local_panorama_bp'));

        $mform->addElement('textarea', 'client_other',
                get_string('other', 'local_panorama_bp'),
                array('style' => 'width: 275px;', 'rows' => '7'));

        $mform->addElement('text', 'number_users',
                get_string('num_users', 'local_panorama_bp'),
                array('style' => 'width: 75px;'));

        $this->_form->addElement('html', '</span>'); //Close custom_format
    }

    function loadData() {
        
    }

    function isComplete() {
        
    }

}

?>
