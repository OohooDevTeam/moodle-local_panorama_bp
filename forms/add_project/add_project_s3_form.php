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
require_once("$CFG->dirroot/local/panorama_bp/lib.php");
require_once("$CFG->dirroot/local/panorama_bp/classes/quote_table/quote_table.php");

class add_prj_form extends moodleform {

    private $bpid;

    function definition() {
        global $DB, $PAGE;
        $PAGE->navbar->add(get_string('stage_1', 'local_panorama_bp'));

        //Load plugin config info.
        load_bp_config();

        //Add the "General" Section.
        $this->addGeneralSection();

        //Add the description/cost section.
        $this->addQuoteSection();

        //Load any previously saved data.
        $this->loadData();

        $btn_arr = array();
        $btn_arr[] = $this->_form->createElement('radio', 'accept_w_serv', '',
                'Accept with service agreement');
        $btn_arr[] = $this->_form->createElement('radio', 'accept_w_serv', '',
                'Accept without service agreement');
        $btn_arr[] = $this->_form->createElement('radio', 'accept_w_serv', '',
                'Reject');
        
        $this->_form->addGroup($btn_arr);
        
        $this->add_action_buttons();
    }

    /**
     * A section that holds general information about a project. In this form
     * it is no longer editable. It just lets the user know what project they
     * are editing.
     */
    private function addGeneralSection() {
        $mform = $this->_form;

        $mform->addElement('header', '',
                get_string('general', 'local_panorama_bp'));
        //Custom format makes the input bozes follow my css rules.
        $mform->addElement('html', '<span class="custom_format">');

        $mform->addElement('text', 'project_name',
                get_string('project_name', 'local_panorama_bp'),
                array('disabled' => 'disabled'));

        $mform->addElement('html', '</span>');
    }

    private function addQuoteSection() {
        $mform = $this->_form;

        //Fake data -- Get real data from database when linking this page.
        $test_data = new stdClass();
        $test_data->qty = 2221;
        $test_data->description = "This is a description.";
        $test_data->unit_price = 30.16;

        $quote_table = new quote_table(array($test_data, $test_data, $test_data, $test_data));

        $quote_table::include_javascript('client'); //Include the client javascript;
        $quote_table::include_stylesheet();
        $mform->addElement('header', '',
                get_string('quote', 'local_panorama_bp'));
        $mform->addElement('html', $quote_table->display());
    }

    private function loadData() {
        
    }

}

?>
