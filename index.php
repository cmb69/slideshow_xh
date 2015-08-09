<?php

/**
 * Front-end of Slideshow_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Slideshow
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2015 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3
 * @link      <http://3-magi.net/?CMSimple_XH/Slideshow_XH>
 */

/*
 * Prevent direct access and usage from unsupported CMSimple_XH versions.
 */
if (!defined('CMSIMPLE_XH_VERSION')
    || strpos(CMSIMPLE_XH_VERSION, 'CMSimple_XH') !== 0
    || version_compare(CMSIMPLE_XH_VERSION, 'CMSimple_XH 1.6', 'lt')
) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: text/plain; charset=UTF-8');
    die(<<<EOT
Slideshow_XH detected an unsupported CMSimple_XH version.
Uninstall Slideshow_XH or upgrade to a supported CMSimple_XH version!
EOT
    );
}

/**
 * The version number of the plugin.
 */
define('SLIDESHOW_VERSION', '@SLIDESHOW_VERSION@');

/**
 * Returns the slideshow.
 *
 * @param string $path    The path of the image folder.
 * @param string $options The options in form of a query string.
 *
 * @return string (X)HTML.
 */
function slideshow($path, $options = '')
{
    return Slideshow_Controller::main($path, $options);
}

Slideshow_Controller::dispatch();

?>
