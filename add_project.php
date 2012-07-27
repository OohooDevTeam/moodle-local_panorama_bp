<?php

require_once("$CFG->libdir/formslib.php");

class add_prj_form extends moodleform {

    function definition() {
        print_object($_REQUEST);
        $mform = & $this->_form;

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
        $this->add_action_buttons();


        //--- Phases -------------------------------
        $this->addPhasesSection();
    }

    /**
     * Adds the client information section to the form. 
     */
    function addClientInfoSection() {
        $mform = &$this->_form;
        $mform->addElement('header', 'client_info', 'Client Information');

        //Format the css for the double column section.
        $mform->addElement('html', '<style>'); {   //Format the title next to the input boxes.
            $mform->addElement('html', '#client_info {');
            $mform->addElement('html', 'overflow: hidden;');
            $mform->addElement('html', '}');

            $mform->addElement('html', '#client_info .fitem .fitemtitle {');
            $mform->addElement('html', '    width: 115px');
            $mform->addElement('html', '}');

            //Format the input boxes
            $mform->addElement('html', '#client_info .fitem .felement {');
            $mform->addElement('html', '    width: 210px;');
            $mform->addElement('html', '    margin-left: 120px;');
            $mform->addElement('html', '}');
        }
        $mform->addElement('html', '</style>');

        $mform->addElement('html', '<div style="display: table">'); {
            $mform->addElement('html', '<div style="display: table-row">'); {

                //First Column.
                $mform->addElement('html',
                        '<div style="display: table-cell; width: 370px;">'); {

                    //CRM Contact Info
                    $crmContacts = array();

                    $mform->addElement('select', 'crmcontact',
                            get_string('crmcontact', 'local_panorama_bp'),
                            $crmContacts,
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    //Add script to this select box that changes the contact information based on the crm selection.


                    /* Note: Border box style makes everything exactly the same size no matter what the padding or border width */
                    $mform->addElement('text', 'company',
                            get_string('company', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'client_name',
                            get_string('client_name', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'client_email',
                            get_string('client_email', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'client_phone',
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
                    $mform->addElement('text', 'moodle',
                            get_string('moodle', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('text', 'browsers',
                            get_string('browsers', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('textarea', 'other',
                            get_string('other', 'local_panorama_bp'),
                            array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;',
                        'rows' => '7'));

                    $mform->addElement('text', 'num_users',
                            get_string('num_users', 'local_panorama_bp'),
                            array('style' => 'width: 50px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

                    $mform->addElement('date_selector', 'valid_until',
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

        $mform->addElement('textarea', 'problem',
                get_string('problem', 'local_panorama_bp'),
                'rows="12" cols="100"');

        $mform->addElement('textarea', 'specifications',
                get_string('specifications', 'local_panorama_bp'),
                'rows="12" cols="100"');

        $mform->addElement('textarea', 'requirements',
                get_string('requirements', 'local_panorama_bp'),
                'rows="12" cols="100"');
    }

    /**
     * Adds the phases section to the form. 
     */
    function addPhasesSection() {
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
                        get_string('phase', 'local_panorama_bp') . ' 1');
        $phase_array[] = &$mform->createElement('submit', 'phase2',
                        get_string('phase', 'local_panorama_bp') . ' 2');
        $phase_array[] = &$mform->createElement('submit', 'phase3',
                        get_string('phase', 'local_panorama_bp') . ' 3');
        $phase_array[] = &$mform->createElement('submit', 'phase4',
                        get_string('phase', 'local_panorama_bp') . ' 4');
        $phase_array[] = &$mform->createElement('submit', 'phase5',
                        get_string('phase', 'local_panorama_bp') . ' 5');
        $phase_array[] = &$mform->createElement('submit', 'phase6',
                        get_string('phase', 'local_panorama_bp') . ' 6');

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
                get_string('view_all', 'local_panorama_bp'));
    }

}

?>