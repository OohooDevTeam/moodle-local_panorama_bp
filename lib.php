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
 *  Library file for this plugin.
 */

/**
 * This function loads the config information from the settings record. 
 */
function load_bp_config() {
    global $CFG, $DB;
    //get panorama_bp_config
    $config_bp = $DB->get_record('panorama_bp_config', array('id' => 1));

    $CFG->panaorama_sugarCRM_url = "$config_bp->sugarcrm_url";
    $CFG->panaorama_sugarCRM_username = $config_bp->sugarcrm_username;
    $CFG->panaorama_sugarCRM_pwd = $config_bp->sugarcrm_pwd;
}

/**
 * This function logs us into soap/sugarCRM so that we can make changes to their 
 * database.
 * 
 * @return  An assoicative array of soap login information:
 * 
 *              'seassion_id' => a session key that results from a 
 *                  succesful login.
 *              'client' => a "nusoap_client" object.
 */
function soapLogin() {
    global $CFG;
    $url = "$CFG->panaorama_sugarCRM_url/service/v4/soap.php?wsdl";
    $username = $CFG->panaorama_sugarCRM_username;
    $password = $CFG->panaorama_sugarCRM_pwd;

    //require NuSOAP
    require_once("nusoap/lib/nusoap.php");

    //retrieve WSDL
    $client = new nusoap_client($url, 'wsdl');

    //display errors
    $err = $client->getError();
    if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(),
                ENT_QUOTES) . '</pre>';
        exit();
    }

    //login ----------------------------------------------------------------

    $login_parameters = array(
        'user_auth' => array(
            'user_name' => $username,
            'password' => md5($password),
            'version' => '1'
        ),
        'application_name' => 'SoapTest',
        'name_value_list' => array(
        ),
    );
    $login_result = $client->call('login', $login_parameters);

//    echo '<pre>';
//    print_r($login_result);
//    echo '</pre>';
    //get session id
    $session_id = $login_result['id'];

    return array('session_id' => $session_id, 'client' => $client);
}

/**
 * Get a list of all the contacts contacts in sugarCRM.
 * 
 * For details on the contact database structure in sugarCRM see:
 * http://{Your Sugar CRM Install}/index.php?module=ModuleBuilder&action=index&type=studio#ajaxUILoc=&mbContent=module%3DModuleBuilder%26action%3Dmodulefields%26view_package%3Dstudio%26view_module%3DContacts
 * 
 * @param type $where   The SQL WHERE clause (excluding "WHERE")
 * @param type $orderby The SQL ORDER BY  clause (excluding "ORDER BY")
 * @param type $soap    An assoicative array of soap login information:
 * 
 *                          'seassion_id' => a session key that results from a 
 *                              succesful login.
 *                          'client' => a "nusoap_client" object.
 * 
 *                      This array is easily obtained by calling soapLogin()
 * @return stdClass[]   An array of all the contact information. Each element in
 *                      the array will have the following parameters:
 *                          'id' -> The database id number of the client in 
 *                              sugarCRM
 *                          'organization' -> The first listed company that is
 *                              associated with the contact. *
 *                          'name' -> The contact's name
 *                          'email' -> The contact's email
 *                          'phone' -> The contact's work phone number. **
 * 
 *  Notes:
 *      *   This was a judgment call. We could either return
 *          no organization or we could return any one of the companies that 
 *          he/she is associated with.
 *      **  This was also a judgement call. We could return either work, home
 *          or mobile phone numbers.
 * 
 */
function sugarCRM_contacts($where = '', $orderby = '', $soap = false) {

    //Check if they passed in a soap variable.
    if (!$soap) {
        //If not then login and generate one.
        $soap = soapLogin();
    }

    //Extract the required information from soap variable.
    $session_id = $soap['session_id'];
    $client = $soap['client'];

    //get list of all contacts -------------------------------------------------
    $get_entry_list_parameters = array(
        //session id
        'session' => $session_id,
        //The name of the module from which to retrieve records
        'module_name' => 'Contacts',
        //The SQL WHERE clause without the word "where".
        'query' => $where,
        //The SQL ORDER BY clause without the phrase "order by".
        'order_by' => $orderby,
        //The record offset from which to start.
        'offset' => '0',
        //Optional. A list of fields to include in the results.
        'select_fields' => array(
            'id',
            'first_name',
            'last_name',
//            'title',
//            'department',
            'email1',
            'phone_work'
        ),
        /*
          A list of link names and the fields to be returned for each link name.
         */
        'link_name_to_fields_array' => array(
        ),
        //The maximum number of results to return.
        'max_results' => '20',
        //To exclude deleted records
        'deleted' => '0',
        //If only records marked as favorites should be returned.
        'Favorites' => false,
    );



    $get_entry_list_result = $client->call('get_entry_list',
            $get_entry_list_parameters);


    if (array_key_exists('entry_list', $get_entry_list_result)) {
        $contact_list = $get_entry_list_result['entry_list'];
    } else {
        //Return the erroring object so they can deal with it.
        return $contact_list;
    }


    //If there were no result return an empty array.
    if (!$contact_list) {
        return array();
    } else {
        //Convert to a readable array and fill in missing information then 
        //return.
        return extract_contact_info($contact_list, $soap);
    }
}

/**
 * Takes in the contact information array and converts it to a more readable
 * object. Also fills in missing organization parameter.
 * 
 * @param Array[] $contact_list     The 'entry_list' parameter from soap/sugar 
 *                                  get_entry_list method call.
 *                                  
 *                                  Example:
 *                                  $client->call('get_entry_list', $params)['entry_list']
 * 
 * @param type $soap        An assoicative array of soap login information:
 * 
 *                              'session_id' => a session key that results from
 *                                  a succesful login.
 *                              'client' => a "nusoap_client" object. 
 * @return  [See @return for function sugarCRM_contacts]
 */
function extract_contact_info($contact_list, $soap) {

    //Extract the required information from soap variable.
    $session_id = $soap['session_id'];
    $client = $soap['client'];

    $contacts = array();

    foreach ($contact_list as $entry) {
        $relationship_params = array(
            'session' => $session_id,
            //The name of the module from which to retrieve records
            'module_name' => 'Contacts',
            'module_id' => $entry['id'],
            'related_fields' => array(
                'name'
            ),
            'link_field_name' => 'accounts',
            'related_module_query' => '',
            'deleted' => false);

        $relationship_result = $client->call('get_relationships',
                $relationship_params);

        $contact = new stdClass();

        //Set the name of the first account in the list.
        $account = $relationship_result['entry_list'];

        if (count($account)) {
            //0th element is the name
            $contact->organization = $account[0]['name_value_list'][0]['value'];
        }


        $contact->id = $entry['name_value_list'][0]['value'];
        //Firstname . . Lastname
        $contact->name = $entry['name_value_list'][1]['value'] . ' ' . $entry['name_value_list'][2]['value'];
//        $contact->title = $entry['name_value_list'][3]['value'];
//        $contact->department = $entry['name_value_list'][4]['value'];
        $contact->email = $entry['name_value_list'][3]['value'];
        $contact->phone = $entry['name_value_list'][4]['value'];

        $contacts[] = $contact;
    }

    return $contacts;
}

/**
 * Get a list of all the projects and associated information.
 * 
 * @global $DB          The moodle database object
 * @return stdClass[]   An array of objects with all the fields in the database 
 *                      table panorama_bp.
 */
function get_projects() {
    global $DB;

    //Get database table of all the projects.
    $projects = $DB->get_records('panorama_bp');

    //Log into soap.
    $soap = soapLogin();

    //Make sure each projects is completely filled before returning.
    foreach ($projects as $key => $project) {

        //Add any missing information. -- If they used a sugarCRM contact as a base it
        //will fill out all the sugarCRM info.
        $full_project = add_outstanding_info($project, $soap);

        $projects[$key] = $full_project;
    }

    return $projects;
}

/**
 * Get a single project record from the database.
 * 
 * @global $DB          The moodle database object
 * @param type $id      The id of the project you wish to get.
 * @return stdClas      An object with all the fields in the database 
 *                      table panorama_bp. */
function get_project($id) {
    global $DB;

    //Get the database record.
    $project = $DB->get_record('panorama_bp', array('id' => $id));

    //Add any missing information. -- If they used a sugarCRM contact as a base it
    //will fill out all the sugarCRM info.
    $full_project = add_outstanding_info($project);

    return $full_project;
}

/**
 * Adds any missing information into the following fields if possible:
 *  - project_contact_name
 *  - project_contact_email
 *  - project_contact_phone
 * 
 * @param stdClass $project The project record. Should have all the fields from 
 *                          the database table panorama_bp. -- Easily grabbed 
 *                          from $DB->get_record('panorama_bp', ...)

 * @param type $soap        An assoicative array of soap login information:
 * 
 *                              'session_id' => a session key that results from
 *                                  a succesful login.
 *                              'client' => a "nusoap_client" object.
 * @return stdClass         An object with the same fields as the passed in 
 *                          project but with the fields project_contact_name, 
 *                          project_contact_email, and project_contact_phone
 *                          filled in if they were missing.
 */
function add_outstanding_info($project, $soap = false) {

    //Check if they passed in a soap variable.
    if (!$soap) {
        //If not then login and generate one.
        $soap = soapLogin();
    }

    //Check if the project has a sugarCRM contact id. -- If not then we're done 
    //and return it.
    if ($project->crm_contact_id != '0') {

        //Get the contact information from sugarCRM
        $contact_array = sugarCRM_contacts("contacts.id = '$project->crm_contact_id'",
                '', $soap);

        //returns an array so grab the 0th element.
        $contact = $contact_array[0];

        //Fill out all the contact information.
        $project->project_contact_name = $contact->name;
        $project->project_contact_phone = $contact->phone;
        $project->project_contact_email = $contact->email;
    }

    return $project;
}

/**
 * Takes in a status code and returns a readable string that the code represents
 * 
 * Note: hese strings are colored with html span tags.
 * 
 * @param $statusCode
 * @return string
 */
function status_code_string($statusCode) {
    switch ($statusCode) {
        case 0:
            return '<span style="color: #ff0000">' . get_string('pending',
                            'local_panorama_bp') . '</span>';
        case 1:
            return '<span style="color: #dddd11">' . get_string('active',
                            'local_panorama_bp') . '</span>';
        case 2:
            return '<span style="color: #00ff00">' . get_string('complete',
                            'local_panorama_bp') . '</span>';
        default:
            return 'Error';
    }
}

/**
 * Generate an html table of tasks with links attached so that they can be edited.
 * 
 * @param type $tasks   The list of tasks to put in the table.
 * @param type $phase   Whether or not to add in the phase column.
 * @return string
 */
function generate_task_table($tasks, $phase = false) {
    global $CFG;
    $table = '<table style="width: 100%;">'; {
        //Table header.
        $table .= '<tr>';
        {
            $table .= '<td>' . 'id' . '</td>';

            //If phase was to be included then add column for the phase.
            if ($phase) {
                $table .= '<td>' . get_string('phase', 'local_panorama_bp') . '</td>';
            }

            $table .= '<td>' . get_string('description', 'local_panorama_bp') . '</td>';
            $table .= '<td>' . get_string('comments', 'local_panorama_bp') . '</td>';
            $table .= '<td>' . get_string('timeline', 'local_panorama_bp') . '</td>';
            $table .= '<td>' . get_string('status', 'local_panorama_bp') . '</td>';
        }

        $table .= '</tr>';


        //Table Body
        foreach ($tasks as $task) {
            $table .= '<tr>';
            {
                $table .= '<td>' .
                        "<a href = '$CFG->wwwroot/local/panorama_bp/tasks.php?val=$task->phase&bpid=$task->bp_id&taskid=$task->id'>" .
                        $task->id .
                        "</a>" .
                        '</td>';

                //If phase was to be included then add column for the phase.
                if ($phase) {
                    $table .= '<td>' . $task->phase . '</td>';
                }

                $table .= '<td>' . $task->description . '</td>';
                $table .= '<td>' . $task->comments . '</td>';
                $table .= '<td>' . $task->time_details . '</td>';
                $table .= '<td>' . status_code_string($task->status) . '</td>';
            }
            $table .= '</tr>';
        }
    }
    $table .= '</table>';

    return $table;
}

?>
