<?php

require_once("$CFG->libdir/formslib.php");

class task_form extends moodleform {

    function definition() {
        global $DB;

        $val = required_param('val', PARAM_INT);
        $bpid = required_param('bpid', PARAM_INT);

        print_object($_REQUEST);
        $mform = & $this->_form;

        $attr = &$mform->_attributes;
        $attr['action'] = $attr['action'] . '?val=' . $val . '&bpid=' . $bpid;

        $mform->addElement('header', '',
                get_string('phase', 'local_panorama_bp') . ' ' . $val);


        //Add css for the table
        $mform->addElement('html', '<style>');
        $cssString = "
            table, tr, th, td {
                border: 1px solid black;
                padding-left: 25px;
                padding-right: 25px;

                text-align: center;
                font-size: 1.0em;
            }

            table {
                border-collapse: collapse;
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
            
        ";

        $mform->addElement('html', $cssString);
        $mform->addElement('html', '</style>');

        $mform->addElement('html', '<table style="width: 100%;">'); {
            $mform->addElement('html', '<tr>');
            {
                $mform->addElement('html',
                        '<td>' . get_string('description', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('comments', 'local_panorama_bp') . '</td>');
                $mform->addElement('html',
                        '<td>' . get_string('timeline', 'local_panorama_bp') . '</td>');
            }
            $mform->addElement('html', '</tr>');


            //Table Body
            $phases = $DB->get_records('panorama_bp_phases',
                    array('phase' => $val));

            foreach ($phases as $phase) {
                $mform->addElement('html', '<tr>');
                {
                    $mform->addElement('html',
                            '<td>' . $phase->description . '</td>');
                    $mform->addElement('html',
                            '<td>' . $phase->comments . '</td>');
                    $mform->addElement('html',
                            '<td>' . $phase->time_details . '</td>');
                }
                $mform->addElement('html', '</tr>');
            }
        }
        $mform->addElement('html', '</table>');

        $mform->addElement('header', '',
                get_string('add_new_task', 'local_panorama_bp'));
        $mform->addElement('textarea', 'decription',
                get_string('description', 'local_panorama_bp'),
                'rows="7" style="width: 65%"');
        $mform->addElement('textarea', 'comments',
                get_string('comments', 'local_panorama_bp'),
                'rows="7" style="width: 65%"');

        $mform->addElement('text', 'timeline',
                get_string('timeline', 'local_panorama_bp'),
                array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

        $statuses = array();

        $mform->addElement('select', 'status',
                get_string('status', 'local_panorama_bp'), $statuses,
                array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

        $buttonarr = array();

        $buttonarr[] = &$mform->createElement('submit', 'save_cont',
                        get_string('save_and_cont', 'local_panorama_bp'));
        $buttonarr[] = &$mform->createElement('submit', 'save_rtrn',
                        get_string('save_and_rtrn', 'local_panorama_bp'));

        $mform->addGroup($buttonarr);
    }

}

?>
