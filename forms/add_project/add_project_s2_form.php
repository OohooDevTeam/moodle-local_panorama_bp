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

        $quote_table = new quote_table(array());
        $quote_table::include_javascript();
        $quote_table::include_stylesheet();
        $mform->addElement('header', '',
                get_string('quote', 'local_panorama_bp'));
        $mform->addElement('html', $quote_table->display());
        
        $mform->addElement('checkbox', 'deposit_required',
                get_string('deposit_required', 'local_panorama_bp'));
        $mform->addElement('html', '<hr/>');
        $mform->addElement('checkbox', 'comfirm_complete',
                get_string('confirm_complete', 'local_panorama_bp'));
    }

    private function loadData() {
        
    }

}

?>
