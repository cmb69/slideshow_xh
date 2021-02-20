<?php

/**
 * Copyright 2012-2021 Christoph M. Becker
 *
 * This file is part of Slideshow_XH.
 *
 * Slideshow_XH is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Slideshow_XH is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Slideshow_XH.  If not, see <http://www.gnu.org/licenses/>.
 */

use Slideshow\InfoCommand;
use Slideshow\SystemChecker;
use Slideshow\View;

if (!defined('CMSIMPLE_XH_VERSION')) {
    exit;
}

XH_registerStandardPluginMenuItems(false);
if (XH_wantsPluginAdministration('slideshow')) {
    $o .= print_plugin_admin('off');
    switch ($admin) {
        case '':
            $title = 'Slideshow ' . SLIDESHOW_VERSION;
            ob_start();
            (new InfoCommand(new View, new SystemChecker))();
            $o .= ob_get_clean();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'slideshow');
    }
}
