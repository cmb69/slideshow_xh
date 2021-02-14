<?php

/**
 * The system checkers.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Slideshow
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Slideshow_XH
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
        return defined('CMSIMPLE_XH_VERSION')
            && strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') === 0
            && version_compare(CMSIMPLE_XH_VERSION, "CMSimple_XH $version", 'ge');
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
     * Checks, that magic quotes are off.
     *
     * @return int The status code.
     */
    public function checkMagicQuotes()
    {
        return !get_magic_quotes_runtime() ? self::OK : self::FAIL;
    }
    
    /**
     * Checks whether UTF-8 encoding is configured.
     *
     * @return int The status code.
     *
     * @global array The localization of the core.
     */
    public function checkEncoding()
    {
        global $tx;
        
        return strtoupper($tx['meta']['codepage']) == 'UTF-8'
            ? self::OK
            : self::WARN;
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

?>
