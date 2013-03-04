<?php

/**
 * Back-end of Slideshow_XH.
 *
 * @package	Slideshow
 * @copyright	Copyright (c) 2012-2013 Christoph M. Becker <http://3-magi.net/>
 * @license	http://www.gnu.org/licenses/gpl.html GNU GPLv3
 * @version	$Id$
 * @link	<http://3-magi.net/?CMSimple_XH/Slideshow_XH>
 */


/*
 * Prevent direct access.
 */
if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


/**
 * Returns the plugin version information view.
 *
 * @global array  The paths of system files and folders.
 * @return string  The (X)HTML.
 */
function Slideshow_version()
{
    global $pth;

    return '<h1><a href="http://3-magi.net/?CMSimple_XH/Slideshow_XH">Slideshow_XH</a></h1>'
        . tag('img src="' . $pth['folder']['plugins'] . 'slideshow/slideshow.png"'
              . ' style="float:left;margin-right:1em" alt="Plugin Icon"')
        . '<p>Version: ' . SLIDESHOW_VERSION . '</p>'
        . '<p>Copyright &copy; 2012 <a href="http://3-magi.net/">Christoph M. Becker</a></p>'
        . '<p style="text-align:justify">This program is free software: you can redistribute it and/or modify'
        . ' it under the terms of the GNU General Public License as published by'
        . ' the Free Software Foundation, either version 3 of the License, or'
        . ' (at your option) any later version.</p>'
        . '<p style="text-align:justify">This program is distributed in the hope that it will be useful,'
        . ' but WITHOUT ANY WARRANTY; without even the implied warranty of'
        . ' MERCHAN&shy;TABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the'
        . ' GNU General Public License for more details.</p>'
        . '<p style="text-align:justify">You should have received a copy of the GNU General Public License'
        . ' along with this program.  If not, see'
        . ' <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.</p>';
}


/**
 * Returns the requirements information view.
 *
 * @global array  The paths of system files and folders.
 * @global array  The localization of the core.
 * @global array  The localization of the plugins.
 * @return string  The (X)HTML.
 */
function Slideshow_systemCheck() // RELEASE-TODO
{
    global $pth, $tx, $plugin_tx;

    define('SLIDESHOW_PHP_VERSION', '4.0.7');
    $ptx = $plugin_tx['slideshow'];
    $imgdir = $pth['folder']['plugins'] . 'slideshow/images/';
    $ok = tag('img src="' . $imgdir . 'ok.png" alt="ok"');
    $warn = tag('img src="' . $imgdir . 'warn.png" alt="warning"');
    $fail = tag('img src="' . $imgdir . 'fail.png" alt="failure"');
    $o = '<h4>' . $ptx['syscheck_title'] . '</h4>'
        . (version_compare(PHP_VERSION, SLIDESHOW_PHP_VERSION) >= 0 ? $ok : $fail)
        . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_phpversion'], SLIDESHOW_PHP_VERSION)
        . tag('br');
    foreach (array() as $ext) {
	$o .= (extension_loaded($ext) ? $ok : $fail)
            . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_extension'], $ext) . tag('br');
    }
    $o .= (!get_magic_quotes_runtime() ? $ok : $fail)
	. '&nbsp;&nbsp;' . $ptx['syscheck_magic_quotes'] . tag('br') . tag('br');
    $o .= (strtoupper($tx['meta']['codepage']) == 'UTF-8' ? $ok : $warn)
	. '&nbsp;&nbsp;' . $ptx['syscheck_encoding'] . tag('br') . tag('br');
    foreach (array('config/', 'languages/') as $folder) {
	$folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
    }
    foreach ($folders as $folder) {
	$o .= (is_writable($folder) ? $ok : $warn)
	    . '&nbsp;&nbsp;' . sprintf($ptx['syscheck_writable'], $folder) . tag('br');
    }
    return $o;
}


/*
 * Handle the plugin administration.
 */
if (isset($slideshow) && $slideshow == 'true') {
    $o .= print_plugin_admin('off');
    switch ($admin) {
    case '':
        $o .= Slideshow_version() . tag('hr') . Slideshow_systemCheck();
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}


?>
