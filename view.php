<?php
//    -----    Includes    -----    //
require_once(dirname(dirname(dirname((__FILE__)))) . '/config.php');
require_once(dirname(__FILE__) . '/add_project_form.php');

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
//Ouput the header.
echo $OUTPUT->header();

//    -----    MAIN CONTENT    -----    //
?>
<style>
    table, tr, th, td {
        border: 1px solid black;
        padding-left: 25px;
        padding-right: 25px;

        text-align: center;
        font-size: 1.0em;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }


    tr {
        background-color: #eee;
    }

    tr:first-child {
        background-color: #ddd; 
        border-bottom-width: 2px;
        border-bottom-color: black;
        font-weight: bold;

    }

    tr:nth-child(2n) {
        background-color: #fff;
    } 
</style>

<table>
    <tr>
        <td><?php
echo get_string('title', 'local_panorama_bp');
?></td>
        <td><?php echo get_string('client', 'local_panorama_bp');
?></td>
        <td><?php echo get_string('project_contact', 'local_panorama_bp');
?></td>
        <td><?php echo get_string('it_contact', 'local_panorama_bp');
?></td>
        <td><?php echo get_string('phase', 'local_panorama_bp');
?></td>
    </tr>

    <?php
    $projects = get_projects();

    foreach ($projects as $project) {
        ?>

        <tr>
            <td><? echo $project->project_name; ?></td>
            <td><? echo $project->organization; ?></td>
            <td><? echo $project->project_contact_name . ' - ' . $project->project_contact_phone; ?></td>
            <td><? echo $project->it_contact_name . ' - ' . $project->it_contact_phone; ?></td>
            <td><? echo 0; ?></td>
        </tr>

        <?php
    }
    ?>

</table>

<input type="submit" value="<? echo get_string('add_project',
            'local_panorama_bp');
    ?>" onclick="window.location = '<? echo $CFG->wwwroot ?>/local/panorama_bp/add_project.php'; return false;"/>

<?php
//    -----    END OF MAIN CONTENT    -----    //
//Output the footer
echo $OUTPUT->footer();
?>
