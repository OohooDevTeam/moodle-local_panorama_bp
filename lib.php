<?php

function load_bp_config() {
    global $CFG, $DB;
    //get panorama_bp_config
    $config_bp = $DB->get_record('panorama_bp_config', array('id' => 1));

    $CFG->panaorama_sugarCRM_url = "$config_bp->sugarcrm_url/service/v4/soap.php?wsdl";
    $CFG->panaorama_sugarCRM_username = $config_bp->sugarcrm_username;
    $CFG->panaorama_sugarCRM_pwd = $config_bp->sugarcrm_pwd;
}

function sugarCRM_contacts($url = null, $username = null, $password = null) {
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
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
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

//get list of records -------------------------------------------------------

    $get_entry_list_parameters = array(
        //session id
        'session' => $session_id,
        //The name of the module from which to retrieve records
        'module_name' => 'Contacts',
        //The SQL WHERE clause without the word "where".
        'query' => "",
        //The SQL ORDER BY clause without the phrase "order by".
        'order_by' => "",
        //The record offset from which to start.
        'offset' => '0',
        //Optional. A list of fields to include in the results.
        'select_fields' => array(
            'id',
            'first_name',
            'last_name',
            'title',
            'department'
        ),
        /*
          A list of link names and the fields to be returned for each link name.
          Example: 'link_name_to_fields_array' => array(array('name' => 'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))
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

    $get_entry_list_result = $client->call('get_entry_list', $get_entry_list_parameters);

    echo '<pre>';
    print_r($get_entry_list_result);
    echo '</pre>';
}

?>
