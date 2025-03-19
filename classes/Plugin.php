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

        self::registerUserFunctions();
        /** @psalm-suppress UndefinedConstant */
        if (XH_ADM) {
            XH_registerStandardPluginMenuItems(false);
            if (XH_wantsPluginAdministration('slideshow')) {
                $o .= print_plugin_admin('off')
                    . self::admin($admin);
            }
        }
    }

    /**
     * @return void
     * @psalm-suppress UnusedVariable
     */
    private static function registerUserFunctions()
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $rc = new ReflectionClass("\\Slideshow\\Plugin");
        foreach ($rc->getMethods(ReflectionMethod::IS_PUBLIC) as $rm) {
            if (substr_compare($rm->getName(), "Command", -strlen("Command")) === 0) {
                $name = $rm->getName();
                $lcname = substr(strtolower($name), 0, -strlen("Command"));
                $params = $args = [];
                foreach ($rm->getParameters() as $rp) {
                    $param = $arg = "\${$rp->getName()}";
                    if ($rp->isOptional()) {
                        $default = var_export($rp->getDefaultValue(), true);
                        $param .= " = " . $default;
                    }
                    $params[] = $param;
                    $args[] = $arg;
                }
                $parameters = implode(", ", $params);
                $arguments = implode(", ", $args);
                $body = "return \\Slideshow\\Plugin::$name($arguments);";
                $code = "function $lcname($parameters) {\n\t$body\n}";
                eval($code);
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
        global $title;

        $title = 'Slideshow ' . self::VERSION;
        ob_start();
        (new InfoCommand(new View(), new SystemChecker()))();
        return ob_get_clean();
    }

    /**
     * Returns the slideshow.
     *
     * @param string $path    The path of the image folder.
     * @param string $options The options in form of a query string.
     *
     * @return string (X)HTML.
     */
    public static function slideshowCommand($path, $options = '')
    {
        ob_start();
        (new MainCommand(new ImageRepo(), new View()))($path, $options);
        return ob_get_clean();
    }
}
