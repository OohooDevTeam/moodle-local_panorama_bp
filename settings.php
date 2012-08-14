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
