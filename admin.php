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
 * Returns the plugin information view.
 *
 * @return string  The (X)HTML.
 */
function Slideshow_info() // RELEASE-TODO
{
    global $pth, $tx, $plugin_tx;

    $ptx = $plugin_tx['slideshow'];
    $phpVersion = '4.0.7';
    foreach (array('ok', 'warn', 'fail') as $state) {
        $images[$state] = "{$pth['folder']['plugins']}slideshow/images/$state.png";
    }
    $checks = array();
    $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)] =
        version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok' : 'fail';
    foreach (array() as $ext) {
	$checks[sprintf($ptx['syscheck_extension'], $ext)]
            = extension_loaded($ext) ? 'ok' : 'fail';
    }
    $checks[$ptx['syscheck_magic_quotes']] =
        !get_magic_quotes_runtime() ? 'ok' : 'fail';
    $checks[$ptx['syscheck_encoding']] =
        strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
    foreach (array('config/', 'languages/') as $folder) {
	$folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
    }
    foreach ($folders as $folder) {
	$checks[sprintf($ptx['syscheck_writable'], $folder)] =
            is_writable($folder) ? 'ok' : 'warn';
    }
    $bag = array(
        'tx' => $ptx,
        'images' => $images,
        'checks' => $checks,
        'icon' => $pth['folder']['plugins'] . 'slideshow/slideshow.png',
        'version' => SLIDESHOW_VERSION
    );
    return Slideshow_view('info', $bag);
}


/*
 * Handle the plugin administration.
 */
if (isset($slideshow) && $slideshow == 'true') {
    $o .= print_plugin_admin('off');
    switch ($admin) {
    case '':
        $o .= Slideshow_info(); // . tag('hr') . Slideshow_systemCheck();
        break;
    default:
        $o .= plugin_admin_common($action, $admin, $plugin);
    }
}


?>
