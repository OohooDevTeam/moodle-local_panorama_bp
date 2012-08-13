<?php

/**
 * This is a class that holds all functions and methods required to make display
 * a quote table and make it functional.
 *
 * @author User
 */
class quote_table {

    private $data;

    function __construct($data) {
        $this->data = $data;
    }

    function display() {
        global $OUTPUT;
        $html = '';
        $html .= '<table id="price_table">';
        {
            //Table Header
            $html .= '<thead>';
            {
                $html .= '<tr>';
                {
                    $html .= '<td class ="qty">' . get_string('qty',
                                    'local_panorama_bp') . '</td>';
                    $html .= '<td class ="description">' . get_string('description',
                                    'local_panorama_bp') . '</td>';
                    $html .= '<td class ="unit_price">' . get_string('unit_price',
                                    'local_panorama_bp') . '</td>';
                    $html .= '<td class ="line_total">' . get_string('line_total',
                                    'local_panorama_bp') . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</thead>';

            //Table Content.
            $html .= '<tbody>';
            {
                //Itemized Pricing Rows.
                $sum = 0;
                foreach ($this->data as $row) {
                    $html .= '<tr>';
                    {
                        $html .= '<td class ="qty"><input name="qty[]" type="text" value="' . $row->qty . '"/></td>';
                        $html .= '<td class ="description"><input name="description[]" type="text" value="' . $row->description . '".></td>';
                        $html .= '<td class ="unit_price"><input name="unit_price[]" type="text" value="' . $row->unit_price . '"/></td>';
                        $html .= '<td class ="line_total">' . ($row->qty * $row->unit_price) . '</td>';
                        $sum += ($row->qty * $row->unit_price);
                    }
                    $html .= '</tr>';
                }
                //Subtotal row.
                $html .= '<tr id="subtotal_row">'; {
                    //Get the add icon.
                    $add_icon = new pix_icon('t/add', '', 'moodle', array('class' => 'iconsmall'));
                    $html .= '<td id = "add_row_cell">' . $OUTPUT->render($add_icon) . '</td>';

                    $html .= '<td colspan="2" id="subtotal" >' . get_string('subtotal',
                                    'local_panorama_bp') . '</td>';
                    $html .= '<td id="total">' . $sum . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }
        $html .= '</table>';

        $html .= '<ul id="aMenu" class="contextMenu">';
        {
            $html .= '<li class="delete"><a href="#remove_row">' . get_string('delete',
                            'local_panorama_bp') . '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    static function include_stylesheet() {
        global $PAGE, $CFG;
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/panorama_bp/classes/quote_table/quote_table.css'));
        $PAGE->requires->css(new moodle_url($CFG->wwwroot . '/local/panorama_bp/js/contextMenu/jquery.contextMenu.css'));
    }

    static function include_javascript() {
        global $PAGE, $CFG;
        //If jquery was already included then PAGE->requires will ignore this line.
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/panorama_bp/js/jquery-1.7.1.js'),
                true);
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/panorama_bp/js/contextMenu/jquery.contextMenu.js'),
                true);
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/panorama_bp/js/jquery.formatCurrency-1.4.0.js'),
                true);
        $PAGE->requires->js(new moodle_url($CFG->wwwroot . '/local/panorama_bp/classes/quote_table/quote_table.js'),
                true);
    }

}

?>
