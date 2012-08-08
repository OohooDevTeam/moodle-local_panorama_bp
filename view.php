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
 * Your primary entry point for this plugin.
 */

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
$PAGE->requires->css(new moodle_url('styles.css'));
//Ouput the header.
echo $OUTPUT->header();

//    -----    MAIN CONTENT    -----    //
?>

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
