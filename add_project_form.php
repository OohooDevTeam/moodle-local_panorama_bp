<?php

require_once("$CFG->libdir/formslib.php");
require_once("lib.php");

class add_prj_form extends moodleform {

    function definition() {
        global $DB;

        $bpid = optional_param('bpid', false, PARAM_INT);

        $mform = & $this->_form;

        //-- General Information -------------------
        $this->addGeneralInfoSection();

        //--- Client Information -------------------
        $this->addClientInfoSection();

        //--- Goals --------------------------------
        $this->addGoalsSection();


        // * Add Save and cancel buttons. Anything below this will be saved automatically as it's updated. * //
        //Format save and cancel butotn. (Place it bottom right)
        $mform->addElement('html', '<style>'); {
            $mform->addElement('html', '#fgroup_id_buttonar .felement {');
            $mform->addElement('html', 'float: right;');
            $mform->addElement('html', 'width: auto;');

            $mform->addElement('html', '}');
        }
        $mform->addElement('html', '</style>');

        //Add submit/cancel buttons.
        $this->add_action_buttons();

        if ($bpid) {
            //Set default values
            $project = get_project($bpid);

            if ($project) {

                $mform->addElement('hidden', 'id', $bpid);
                foreach ($project as $key => $value) {
                    $mform->setDefault($key, $value);
                }

                //--- Phases -------------------------------
                $this->addPhasesSection($bpid);
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

        /** ----- CSS ------ **/
        //Format the css for the double column section.
        $mform->addElement('html', '<style>'); {   //Format the title next to the input boxes.
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

        /** ----- Display Table ----- **/
        //Form the display into two columns.
        $mform->addElement('html', '<div style="display: table">'); {
            $mform->addElement('html', '<div style="display: table-row">'); {

                //First Column.
                $mform->addElement('html',
                        '<div style="display: table-cell; width: 370px;">'); {

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
                    $mform->disabledIf('project_contact_name', 'crm_contact_id', 'neq', 0);
                    $mform->disabledIf('project_contact_email', 'crm_contact_id','ne1', 0);
                    $mform->disabledIf('project_contact_phone', 'crm_contact_id','ne1', 0);


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
                $mform->addElement('html', '<div style="display: table-cell;">'); {
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
    function addPhasesSection($bpid) {
        global $CFG;

        $mform = &$this->_form;
        $mform->addElement('header', 'phase_section', 'Phases');

        //Format the phase buttons.
        $mform->addElement('html', '<style>'); {   //Format the title next to the input boxes.
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
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=1&bpid=' . $bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase2',
                        get_string('phase', 'local_panorama_bp') . ' 2',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=2&bpid=' . $bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase3',
                        get_string('phase', 'local_panorama_bp') . ' 3',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=3&bpid=' . $bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase4',
                        get_string('phase', 'local_panorama_bp') . ' 4',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=4&bpid=' . $bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase5',
                        get_string('phase', 'local_panorama_bp') . ' 5',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=5&bpid=' . $bpid . '\';   return false;"');
        $phase_array[] = &$mform->createElement('submit', 'phase6',
                        get_string('phase', 'local_panorama_bp') . ' 6',
                        'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php?val=6&bpid=' . $bpid . '\';   return false;"');

        //Add the phase buttons.
        $mform->addGroup($phase_array, 'phase_buttons');

        $mform->addElement('html',
                get_string('current_phase', 'local_panorama_bp'));
        $mform->addElement('html', '<br/>');
        $mform->addElement('html', '<br/>');

        //Add css for the table
        $mform->addElement('html', '<style>');
        $cssString = "
            #phase_section table, #phase_section tr, #phase_section th, #phase_section td {
                border: 1px solid black;
                padding-left: 25px;
                padding-right: 25px;

                text-align: center;
                font-size: 1.0em;
            }
            
            #fitem_id_view_all_button .felement {
                margin: 0px;
            }

            #phase_section table {
                border-collapse: collapse;
            }


            #phase_section tr {
                background-color: #eee;
            }

            #phase_section tr:first-child {
                background-color: #ddd; 
                border-bottom-width: 2px;
                border-bottom-color: black;
                font-weight: bold;

            }

            #phase_section tr:nth-child(2n) {
                background-color: #fff;
            } 
            
        ";
        $mform->addElement('html', $cssString);
        $mform->addElement('html', '</style>');

        $mform->addElement('html', '<table style="width: 100%;">');
        {
            $mform->addElement('html', '<tr>'); {
                $mform->addElement('html',
                        '<td>' . get_string('phase', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('description', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('comments', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('timeline', 'local_panorama_bp') . '</td>');
            }
            $mform->addElement('html', '</tr>');


            //Table Body
            $mform->addElement('html', '<tr>'); {
                $mform->addElement('html',
                        '<td>' . get_string('phase', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('description', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('comments', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('timeline', 'local_panorama_bp') . '</td>');
            }
            $mform->addElement('html', '</tr>');
        }
        $mform->addElement('html', '</table>');

        $mform->addElement('submit', 'view_all_button',
                get_string('view_all', 'local_panorama_bp'),
                'onclick=" window.location =\'' . $CFG->wwwroot . '/local/panorama_bp/phases.php\';   return false;"');
    }

}

?>
