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
 * Your primary entry point for this plugin.
 */
//    -----    Includes    -----    //
require_once(dirname(dirname(dirname((__FILE__)))) . '/config.php');
//require_once(dirname(__FILE__) . '/add_project_form.php');

include_once('lib.php');


//    -----    Security    -----    //
require_login();

$context = get_context_instance(CONTEXT_SYSTEM);
require_capability('local/panorama_business_process:edit', $context);

load_bp_config();

//    -----    Rendering Info    -----    //
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');
$PAGE->set_url($CFG->wwwroot . "/local/panorama_bp/view.php");
$PAGE->set_title(get_string('pluginname', 'local_panorama_bp'));
$PAGE->set_heading(get_string('pluginname', 'local_panorama_bp'));
//$PAGE->requires->css(new moodle_url('styles.css'));
//Ouput the header.
echo $OUTPUT->header();

//    -----    MAIN CONTENT    -----    //
$pix = new pix_icon('i/cross_red_big', '', 'moodle', array('class' => 'iconsmall'));
echo $OUTPUT->render($pix);
die();
$projects = get_projects();

$data = array();

foreach($projects as $project) {
    $data[] = array(
        $project->project_name,
        $project->organization,
        $project->project_contact_name . ' - ' . $project->project_contact_phone,
        $project->it_contact_name . ' - ' . $project->it_contact_phone
    );
}

$header = array(
    get_string('title', 'local_panorama_bp'),
    get_string('client', 'local_panorama_bp'),
    get_string('project_contact', 'local_panorama_bp'),
    get_string('it_contact', 'local_panorama_bp'),
);

echo create_table($header, $data);

?>

<input type="submit" value="<?
echo get_string('add_project', 'local_panorama_bp');
?>" onclick="window.location = '<? echo $CFG->wwwroot ?>/local/panorama_bp/add_project.php'; return false;"/>

<?php
//    -----    END OF MAIN CONTENT    -----    //
//Output the footer
echo $OUTPUT->footer();
?>
