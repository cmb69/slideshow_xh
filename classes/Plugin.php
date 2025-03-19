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

namespace Slideshow;

use Plib\View;
use ReflectionClass;
use ReflectionMethod;

class Plugin
{
    public const VERSION = "1.3";

    /**
     * @return void
     */
    public static function run()
    {
        global $admin, $o;

        if (XH_ADM) { // @phpstan-ignore-line
            XH_registerStandardPluginMenuItems(false);
            if (XH_wantsPluginAdministration('slideshow')) {
                $o .= print_plugin_admin('off')
                    . self::admin($admin);
            }
        }
    }

    /**
     * @param string $admin
     * @return string
     */
    private static function admin($admin)
    {
        switch ($admin) {
            case "":
                return self::info();
            default:
                return plugin_admin_common();
        }
    }

    /**
     * @return string
     */
    private static function info()
    {
        global $title, $pth, $plugin_tx;

        $title = 'Slideshow ' . self::VERSION;
        $view = new View($pth["folder"]["plugins"] . "slideshow/views/", $plugin_tx["slideshow"]);
        return (new InfoCommand($view, new SystemChecker()))();
    }
}
