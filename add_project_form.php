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
 * form is what acctually displays and allows you to edit/add a project. It will
 * also submit all that information to whatever page called it. -- Which at the
 * point of writing this will always be add_project.php. 
 */
require_once("$CFG->libdir/formslib.php");
require_once("lib.php");

class add_prj_form extends moodleform {

    private $bpid;
    private $viewAll;

    function definition() {
        global $DB;

        $this->bpid = optional_param('bpid', false, PARAM_INT);
        $this->viewAll = optional_param('viewall', false, PARAM_INT);

        $mform = & $this->_form;

        //-- General Information -------------------
        $this->addGeneralInfoSection();

        //--- Client Information -------------------
        $this->addClientInfoSection();

        //--- Goals --------------------------------
        $this->addGoalsSection();


        // * Add Save and cancel buttons. Anything below this will be saved automatically as it's updated. * //
        //Format save and cancel butotn. (Place it bottom right)
        $mform->addElement('html', '<style>');
        {
            $mform->addElement('html', '#fgroup_id_buttonar .felement {');
            $mform->addElement('html', 'float: right;');
            $mform->addElement('html', 'width: auto;');

            $mform->addElement('html', '}');
        }
        $mform->addElement('html', '</style>');

        //Add submit/cancel buttons.
        $this->add_action_buttons();

        //If the project already exists then load it's information.
        if ($this->bpid) {
            //Set default values
            $project = get_project($this->bpid);

            //Make sure that no error occured when getting the project.
            if ($project) {

                $mform->addElement('hidden', 'id', $this->bpid);
                foreach ($project as $key => $value) {
                    $mform->setDefault($key, $value);
                }

                //--- Phases -------------------------------
                $this->addPhasesSection();
            }
        }
    }

    function addGeneralInfoSection() {
        $mform = &$this->_form;
        $mform->addElement('header', 'client_info',
                get_string('general', 'local_panorama_bp'));
        $mform->addElement('text', 'project_name',
                get_string('project_name', 'local_panorama_bp'),
                array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));
    }

    /**
     * Adds the client information section to the form. 
     */
    function addClientInfoSection() {
        $mform = &$this->_form;

        //Load the config info.
        load_bp_config();

        //Load all contact information.
        $contacts = sugarCRM_contacts();

        $mform->addElement('header', 'client_info',
                get_string('client_info', 'local_panorama_bp'));

        /** ----- CSS ------ * */
        //Format the css for the double column section.
        $mform->addElement('html', '<style>');
        {   //Format the title next to the input boxes.
            $mform->addElement('html', '#client_info {');
            $mform->addElement('html', 'overflow: hidden;');
            $mform->addElement('html', '}');

            //Format all the titles. { Title [      Input Area     ]}
            $mform->addElement('html', '#client_info .fitem .fitemtitle {');
            $mform->addElement('html', '    width: 115px');
            $mform->addElement('html', '}');

            //Format the input area's
            $mform->addElement('html', '#client_info .fitem .felement {');
            $mform->addElement('html', '    width: 210px;');
            $mform->addElement('html', '    margin-left: 120px;');
            $mform->addElement('html', '}');
        }
        $mform->addElement('html', '</style>');

        /** ----- Display Table ----- * */
        //Form the display into two columns.
        $mform->addElement('html', '<div style="display: table">');
        {
            $mform->addElement('html', '<div style="display: table-row">');
            {

                //First Column.
                $mform->addElement('html',
                        '<div style="display: table-cell; width: 370px;">');
                {

                    // * Add the contact info selection box. * //
                    //Make the contact info an array that can be passed into the
                    //html quick form.
                    $crmContacts = array(get_string('none', 'local_panorama_bp'));

                    foreach ($contacts as $contact) {
                        $crmContacts[$contact->id] = $contact->name;
                    }

                    //Write the script that fills out information if a contact 
                    //is selected. Start by passing all the contact info into
                    //the script.
                    $contact_list = json_encode($contacts);

                    $crmSelectorScript = <<<SCRIPT
                        var contact_list = $contact_list;
                            
                        //Subtract 1 because we added in the "None" index as 0.
                        var index = this.selectedIndex - 1;
                        
                        //This will occur if none "None" was selected.
                        if(index != -1 ) {
                            var contact = contact_list[index];
                            var organization = document.getElementById('id_organization');
                            organization.value = contact.organization;
                            
                            var client_name = document.getElementById('id_project_contact_name');
                            client_name.value = contact.name;
                            
                            var client_email = document.getElementById('id_project_contact_email');
                            client_email.value = contact.email;
                            
                            var client_phone = document.getElementById('id_project_contact_phone');
                            client_phone.value = contact.phone;
                        }
                        
SCRIPT;
                    //Finally add the selector object.
                    $mform->addElement('select', 'crm_contact_id',
                            get_string('crmcontact', 'local_panorama_bp'),
                            $crmContacts,
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;',
                        'onchange' => $crmSelectorScript));

                    //If the selector is not set to index 0. (Not Used) then disable the client name, email and phone fields.
                    $mform->disabledIf('project_contact_name', 'crm_contact_id',
                            'neq', 0);
                    $mform->disabledIf('project_contact_email',
                            'crm_contact_id', 'ne1', 0);
                    $mform->disabledIf('project_contact_phone',
                            'crm_contact_id', 'ne1', 0);


                    /* Note: Border box style makes everything exactly the same size no matter what the padding or border width */
                    $mform->addElement('text', 'organization',
                            get_string('company', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'project_contact_name',
                            get_string('client_name', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'project_contact_email',
                            get_string('client_email', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'project_contact_phone',
                            get_string('client_phone', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'it_contact_name',
                            get_string('it_contact_name', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'it_contact_email',
                            get_string('it_contact_email', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'it_contact_phone',
                            get_string('it_contact_phone', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));
                }
                $mform->addElement('html', '</div>');


                //Second Column
                $mform->addElement('html', '<div style="display: table-cell;">');
                {
                    //Add the mini header
                    $mform->addElement('html',
                            '<div>' . get_string('software_versions_header',
                                    'local_panorama_bp') . '</div>');

                    /* Note: Border box style makes everything exactly 210px no matter what the padding or border width */
                    $mform->addElement('text', 'client_moodle_version',
                            get_string('moodle', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'client_browsers',
                            get_string('browsers', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('textarea', 'client_other',
                            get_string('other', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;',
                        'rows' => '7'));

                    $mform->addElement('text', 'number_users',
                            get_string('num_users', 'local_panorama_bp'),
                            array('style' => 'width: 50px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('date_selector', 'validity',
                            get_string('valid_until', 'local_panorama_bp'));
                }
                $mform->addElement('html', '</div>');
            }
            $mform->addElement('html', '</div>');
        }
        $mform->addElement('html', '</div>');
    }

    /**
     * Adds the Goals section to the form 
     */
    function addGoalsSection() {
        $mform = &$this->_form;
        $mform->addElement('header', '', 'Goals');

        $mform->addElement('textarea', 'client_goal',
                get_string('problem', 'local_panorama_bp'),
                'rows="12" style="width: 100%"');

        $mform->addElement('textarea', 'client_specifications',
                get_string('specifications', 'local_panorama_bp'),
                'rows="12" style="width: 100%"');

        $mform->addElement('textarea', 'client_needs',
                get_string('requirements', 'local_panorama_bp'),
                'rows="12" style="width: 100%; min-width: 400px;"');
    }

    /**
     * Adds the phases section to the form. 
     */
    function addPhasesSection() {
        global $DB, $CFG;

        $mform = &$this->_form;
        $mform->addElement('header', 'phase_section', 'Phases');

        //Format the phase buttons.
        $mform->addElement('html', '<style>');
        {   //Format the title next to the input boxes.
            $mform->addElement('html', '#fgroup_id_phase_buttons .felement {');
            $mform->addElement('html', '    width: auto;');
            $mform->addElement('html', '    margin: 0px;');
            $mform->addElement('html', '    padding: 0px;');
            $mform->addElement('html', '}');

            $mform->addElement('html', '#fgroup_id_phase_buttons {');
            $mform->addElement('html', '   text-align: center;');
            $mform->addElement('html', '}');
        }
        $mform->addElement('html', '</style>');

        //build the phase buttons.
        $phase_array = array();
        $phase_array[] = &$mform->createElement('submit', 'phase1',
                        get_string('phase', 'local_panorama_bp') . ' 1',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=1&bpid=' . $this->bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase2',
                        get_string('phase', 'local_panorama_bp') . ' 2',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=2&bpid=' . $this->bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase3',
                        get_string('phase', 'local_panorama_bp') . ' 3',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=3&bpid=' . $this->bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase4',
                        get_string('phase', 'local_panorama_bp') . ' 4',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=4&bpid=' . $this->bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase5',
                        get_string('phase', 'local_panorama_bp') . ' 5',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=5&bpid=' . $this->bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase6',
                        get_string('phase', 'local_panorama_bp') . ' 6',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/tasks.php?val=6&bpid=' . $this->bpid . '\';   return false;"');

        //Add the phase buttons.
        $mform->addGroup($phase_array, 'phase_buttons');

        //Add all the tasks.
        if ($this->viewAll) {
            //The text to go above the table header.
            $task_header = get_string('all_tasks', 'local_panorama_bp');
            
            //The text in the button underneath the table.
            $button_string = get_string('view_current', 'local_panorama_bp');
            
            //The tasks we want displayed.
            $tasks = $DB->get_records('panorama_bp_phases', array(), 'phase');
        } else {
            $task_header = get_string('current_tasks', 'local_panorama_bp');
            
            $button_string = get_string('view_all', 'local_panorama_bp');

            //Status 0=>pending 1=>active 2=>complete 
            $tasks = $DB->get_records('panorama_bp_phases', array('status' => 1), 'phase');
        }

        $mform->addElement('html', $task_header);
        $mform->addElement('html', '<br/>');
        $mform->addElement('html', '<br/>');

        $mform->addElement('html', generate_task_table($tasks, true));

        $mform->addElement('submit', 'view_all_button',
                $button_string,
                'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/add_project.php?bpid=' . $this->bpid . '&viewall=' . !$this->viewAll . '\';   return false;"');
    }

}

?>
