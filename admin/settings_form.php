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

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/lib/formslib.php");

class local_panorama_bp_config_form extends moodleform {
    
    
    function definition() {
    $mform =& $this->_form;
    
    $mform->addElement('header', 'local_panorama_bp_config',get_string('config','local_panorama_bp'));
    $mform->addElement('text', 'sugarcrm_url', get_string('sugarcrm_url', 'local_panorama_bp'));
    $mform->addHelpButton('sugarcrm_url', 'sugarcrm_url', 'local_panorama_bp');
    $mform->addElement('text', 'sugarcrm_username', get_string('sugarcrm_username', 'local_panorama_bp'));
    $mform->addHelpButton('sugarcrm_username', 'sugarcrm_username', 'local_panorama_bp');
    $mform->addElement('passwordunmask', 'sugarcrm_pwd', get_string('sugarcrm_pwd', 'local_panorama_bp'));
    $mform->addHelpButton('sugarcrm_pwd', 'sugarcrm_pwd', 'local_panorama_bp');
    $mform->addElement('hidden','id');
    $this->add_action_buttons($cancel=true, $submitlabel=get_string('save_config','local_panorama_bp'));
    }
}
?>
