<?php

/**
 * Copyright 2012-2015 Christoph M. Becker
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

spl_autoload_register('Slideshow_autoload');

/**
 * The autoloader.
 *
 * @param string $className A class name.
 *
 * @return void
 */
function Slideshow_autoload($className)
{
    $parts = explode('\\', $className);
    if ($parts[0] == 'Slideshow') {
        include_once dirname(__FILE__) . '/' . $parts[1] . '.php';
    }
}
