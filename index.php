<?php

// $Id$

/**
 * Front-end of Slideshow_XH.
 *
 * @package   slideshow
 * @copyright Copyright (c) 2012 Christoph M. Becker
 * @license   http://www.gnu.org/licenses/gpl.html GPLv3
 */


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}


define('SLIDESHOW_VERSION', '1alpha1');


/**
 * Returns the list of image files in the given folder.
 *
 * @param  string $path  The path of the folder.
 * @return array
 */
function Slideshow_images($path)
{
    global $pth;

    $imgs = array();
    $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
    $dh = opendir($path);
    while (($fn = readdir($dh)) !== false) {
        if (in_array(strtolower(pathinfo($fn, PATHINFO_EXTENSION)),
                     array('gif', 'jpg', 'png')))
        {
            $ffn = $path . $fn;
            $imgs[] = $ffn;
        }
    }
    closedir($dh);
    natcasesort($imgs);
    return $imgs;
}


/**
 * Returns the options.
 *
 * Defaults are taken from $plugin_cf['slideshow']['default_*'].
 * Those will be overridden with the options in $query.
 *
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
 * @param  string $path  The path of the image folder.
 * @param  string $options  The options in form of a query string..
 * @return string  The (X)HTML.
 */
function Slideshow($path, $options = '')
{
    global $hjs, $onload, $pth, $plugin_cf;
    static $run = 0;

    $pcf = $plugin_cf['slideshow'];
    $validOpts = array('effect', 'delay', 'pause', 'duration');
    $opts = Slideshow_getOpts($options, $validOpts);
    $o = '';
    if (!$run) { // TODO: write to $bjs; if so the init script has to be written to $bjs too
        $o .= '<script type="text/javascript" src="'
            . $pth['folder']['plugins'] . 'slideshow/slideshow.js"></script>';
    }
    $run++;
    $imgs = Slideshow_images($path);
    list($w, $h) = getimagesize($imgs[0]);
    $id = "slideshow_$run";
    $style = "position:relative;width:${w}px;height:${h}px;overflow:hidden";
    $o .= "<div id=\"$id\" class=\"slideshow\" style=\"$style\">";
    foreach ($imgs as $i => $img) {
        $bn = substr(basename($img), 0, -4);
        $first = $i == 0 ? 'display:block;z-index:1' : 'display:none';
        $o .= tag("img src=\"$img\" width=\"$w\" height=\"$h\" style=\"position:absolute;$first\" alt=\"$bn\"");
    }
    $o .= '</div>';
    $o .= "<script type=\"text/javascript\">slideshow.init('$id',"
        . "'$opts[effect]',$opts[delay],$opts[pause],$opts[duration]);</script>";
    return $o;
}

?>
