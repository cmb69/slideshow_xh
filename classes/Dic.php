<?php

/**
 * Copyright (c) Christoph M. Becker
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

use Plib\SystemChecker;
use Plib\View;
use Slideshow\Model\ImageRepo;

class Dic
{
    public static function mainCommand(): MainCommand
    {
        global $pth, $plugin_cf;

        return new MainCommand(
            $pth["folder"]["plugins"] . "slideshow/",
            $pth["folder"]["images"],
            $plugin_cf["slideshow"],
            new ImageRepo(),
            self::view()
        );
    }
    public static function infoCommand(): InfoCommand
    {
        global $pth;

        return new InfoCommand($pth["folder"]["plugins"] . "slideshow/", self::view(), new SystemChecker());
    }

    private static function view(): View
    {
        global $pth, $plugin_tx;

        return new View($pth["folder"]["plugins"] . "slideshow/views/", $plugin_tx["slideshow"]);
    }
}
