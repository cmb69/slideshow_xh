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

/**
 * The system checkers.
 *
 * @category CMSimple_XH
 * @package  Slideshow
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */
class SystemChecker
{
    const OK = 0;
    
    const WARN = 1;
    
    const FAIL = 2;

    /**
     * Checks the required PHP version.
     *
     * @param string $version A minimum version.
     *
     * @return int The status code.
     */
    public function checkPHPVersion($version)
    {
        return version_compare(PHP_VERSION, $version) >= 0
            ? self::OK
            : self::FAIL;
    }
    
    /**
     * Checks the required CMSimple_XH version.
     *
     * @param string $version A minimum version.
     *
     * @return int The status code.
     */
    public function checkXHVersion($version)
    {
        return self::hasXhVersion($version) ? self::OK : self::FAIL;
    }
    
    /**
     * Returns whether we have a certain CMSimple_XH version at least.
     *
     * @param string $version A version number.
     *
     * @return bool
     */
    protected function hasXhVersion($version)
    {
        return version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'ge');
    }

    /**
     * Checks whether a extension is available.
     *
     * @param string $name An extension name.
     *
     * @return int The status code.
     */
    public function checkExtension($name)
    {
        return extension_loaded($name) ? self::OK : self::FAIL;
    }

    /**
     * Checks whether a folder is writable.
     *
     * @param string $filename A filename.
     *
     * @return int The status code.
     */
    public function checkWritability($filename)
    {
        return is_writable($filename) ? self::OK : self::WARN;
    }
}
