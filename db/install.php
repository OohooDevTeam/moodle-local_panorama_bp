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
