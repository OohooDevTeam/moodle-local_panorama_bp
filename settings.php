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
 * Link to CSV course upload
 *
 * @package    local
 * @subpackage panorama_tables
 * @copyright  2012 oohoo IT Services (http://oohoo.biz)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if (has_capability('local/panorama_business_process:edit', $systemcontext)) {

    if (!$ADMIN->locate('panorama')) {
        $ADMIN->add('root',
                new admin_category('panorama', get_string('panorama',
                                'local_panorama_bp')));
    }

    if (!$ADMIN->locate('panorama_settings')) {
        $ADMIN->add('panorama',
                new admin_category('panorama_settings', get_string('panorama_settings',
                                'local_panorama_bp')));
    }

    $ADMIN->add('panorama_settings',
            new admin_externalpage('panoramabp_settings', get_string('panorama_bp',
                            'local_panorama_bp'), "$CFG->wwwroot/local/panorama_bp/admin/settings.php", 'local/panorama_business_process:edit'));
    $ADMIN->add('panorama',
            new admin_externalpage('panoramabp', get_string('panorama_bp',
                            'local_panorama_bp'), "$CFG->wwwroot/local/panorama_bp/view.php", 'local/panorama_business_process:edit'));
}
