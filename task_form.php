<?php

require_once("$CFG->libdir/formslib.php");
require_once("lib.php");

class task_form extends moodleform {

    private $bpid;
    private $taskid;
    private $val;

    function definition() {
        global $DB, $CFG;

        $this->val = required_param('val', PARAM_INT);
        $this->bpid = required_param('bpid', PARAM_INT);
        $this->taskid = optional_param('taskid', false, PARAM_INT);

        $mform = & $this->_form;

        //Add in fields for bpid and phase that will be used for processing.
        $mform->addElement('hidden', 'bp_id', $this->bpid);
        $mform->addElement('hidden', 'phase', $this->val);

        $this->add_phase_table();

        $this->add_task_info_area();

        if ($this->taskid) {
            $mform->addElement("hidden", 'id', $this->taskid);
            $task = $DB->get_record('panorama_bp_phases',
                    array('id' => $this->taskid));
            foreach ($task as $key => $value) {
                $mform->setDefault($key, $value);
            }
        }
    }

    /**
     * Adds a table of tasks that are part of the current phase. This table
     * provides all the informatoin about a given task and contains links that
     * will let you edit the task.
     */
    private function add_phase_table() {
        global $DB, $CFG;

        $mform = & $this->_form;

        $attr = &$mform->_attributes;
        $attr['action'] = $attr['action'] . '?val=' . $this->val . '&bpid=' . $this->bpid;

        //Add an element
        $mform->addElement('header', '',
                get_string('phase', 'local_panorama_bp') . ' ' . $this->val);

        //Table Body
        $phases = $DB->get_records('panorama_bp_phases',
                array('phase' => $this->val));
        
        $mform->addElement('html', generate_task_table($phases));
    }

    /**
     * Adds a section to this mform that allows users to add/edit information on
     * a specific task or add a new task.
     */
    private function add_task_info_area() {

        $mform = & $this->_form;

        $mform->addElement('header', '',
                get_string('add_new_task', 'local_panorama_bp'));
        $mform->addElement('textarea', 'description',
                get_string('description', 'local_panorama_bp'),
                'rows="10" style="width: 500px"');
        $mform->addElement('textarea', 'comments',
                get_string('comments', 'local_panorama_bp'),
                'rows="10" style="width: 500px"');

        $mform->addElement('text', 'time_details',
                get_string('timeline', 'local_panorama_bp'),
                array('style' => 'width: 210px; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box;'));

        $statuses = array(
            get_string('pending', 'local_panorama_bp'),
            get_string('active', 'local_panorama_bp'),
            get_string('complete', 'local_panorama_bp')
        );

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
