<?php

/**
 * Front-end of Slideshow_XH.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Slideshow
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3
 * @version   SVN: $Id$
 * @link      <http://3-magi.net/?CMSimple_XH/Slideshow_XH>
 */

/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * The plugin controller.
 */
require_once $pth['folder']['plugin_classes'] . 'Controller.php';

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
