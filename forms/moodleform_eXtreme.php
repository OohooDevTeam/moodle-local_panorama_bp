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
abstract class moodleform_eXtreme extends moodleform {

    //Note: The css for this is in styles.css.
    function addGrid(array $elements, $width = "100%") {
        $mform = $this->_form;

        //Add the table!
        $mform->addElement('html',
                "<div style='display: table; width: $width;'>");

        //Add each row.
        foreach ($elements as $row) {
            $mform->addElement('html', "<div style = 'display: table-row;'>");
            foreach ($row as $item) {
                $mform->addElement('html', "<div style = 'display: table-cell'>");
                $mform->addElement($item);
                $mform->addElement('html', "</div>");
            }
            $mform->addElement('html', "</div>");
        }

        $mform->addElement('html', "</div>");
    }

}

?>
