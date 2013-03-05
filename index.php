<?php

/**
 * Front-end of Slideshow_XH.
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
 * The version number of the plugin.
 */
define('SLIDESHOW_VERSION', '1beta2');


/**
 * Returns the list of image files in the given folder.
 *
 * @param  string $path  The path of the folder.
 * @param  string $order  `fixed'/`sorted'/`random'.
 * @return array
 */
function Slideshow_images($path, $order)
{
    global $pth;

    $imgs = array();
    $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
    $dh = opendir($path);
    while (($fn = readdir($dh)) !== false) {
        if (in_array(strtolower(pathinfo($fn, PATHINFO_EXTENSION)),
                     array('gif', 'jpeg', 'jpg', 'png')))
        {
            $ffn = $path . $fn;
            $imgs[] = $ffn;
        }
    }
    closedir($dh);
    if ($order == 'random') {
        shuffle($imgs);
    } else {
        natcasesort($imgs);
        if ($order == 'sorted') {
            $n = rand(0, count($imgs) - 1);
            $imgs = array_merge(array_slice($imgs, $n),
                                array_slice($imgs, 0, $n));
        }
    }
    return $imgs;
}


/**
 * Returns an instantiated view template.
 *
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the core.
 * @param string $_template  The path of the template file.
 * @param array $_bag  The variables.
 * @return string
 */
function Slideshow_view($_template, $_bag)
{
    global $pth, $cf;

    $_template = $pth['folder']['plugins'] . 'slideshow/views/' . $_template
        . '.htm';
    $_xhtml = strtolower($cf['xhtml']['endtags']) == 'true';
    unset($pth, $cf);
    extract($_bag);
    ob_start();
    include $_template;
    $view = ob_get_clean();
    if (!$_xhtml) {
        $view = str_replace(' />', '>', $view);
    }
    return $view;
}


/**
 * Returns the options.
 *
 * Defaults are taken from $plugin_cf['slideshow']['default_*'].
 * Those will be overridden with the options in $query.
 *
 * @global array  The configuration of the plugins.
 * @param  string $query  The options given like a query string.
 * @param  array $validOpts  The valid options.
 * @return array
 */
function Slideshow_getOpts($query, $validOpts)
{
    global $plugin_cf;

    $map = array('&lt;' => '<', '&gt;' => '>', '&amp;' => '&', '&quot;' => '"');
    $query = strtr($query, $map);
    parse_str($query, $opts);

    $res = array();
    foreach ($validOpts as $key) {
	$res[$key] = isset($opts[$key])
	    ? ($opts[$key] === '' ? true : $opts[$key])
	    : $plugin_cf['slideshow']["default_$key"];
    }

    return $res;
}


/**
 * Returns the slideshow.
 *
 * @global string  (X)HTML to insert to the end of the `body' element.
 * @global array  The paths of system files and folders.
 * @global array  The configuration of the plugins.
 * @param  string $path  The path of the image folder.
 * @param  string $options  The options in form of a query string.
 * @return string  The (X)HTML.
 */
function Slideshow($path, $options = '')
{
    global $bjs, $pth, $plugin_cf;
    static $run = 0;

    $pcf = $plugin_cf['slideshow'];
    $validOpts = array('order', 'effect', 'easing', 'delay', 'pause',
		       'duration');
    $opts = Slideshow_getOpts($options, $validOpts);
    if (isset($bjs)) {
	$script =& $bjs;
    } else {
	$script =& $o;
    }
    $o = '';
    if (!$run) {
	$src = $pth['folder']['plugins'] . 'slideshow/slideshow.min.js';
        $script .= "<script type=\"text/javascript\" src=\"$src\"></script>";
    }
    $run++;
    $imgs = Slideshow_images($path, $opts['order']);
    list($w, $h) = getimagesize($imgs[0]);
    $id = "slideshow_$run";
    $style = "position:relative;width:100%;height:100%;overflow:hidden";
    $o .= "<div id=\"$id\" class=\"slideshow\" style=\"$style\">";
    foreach ($imgs as $i => $img) {
        $bn = basename($img);
        $bn = substr($bn, 0, strrpos($bn, '.'));
        $first = $i == 0 ? 'display:block;z-index:1' : 'display:none';
        $o .= tag("img src=\"$img\" alt=\"$bn\""
		  . " style=\"position:absolute;$first;width:100%\"");
    }
    $o .= '</div>';
    $script .= "<script type=\"text/javascript\">new slideshow.Show('$id'"
        . ",'$opts[effect]','$opts[easing]',$opts[delay],$opts[pause]"
        . ",$opts[duration]);</script>";
    return $o;
}

?>
