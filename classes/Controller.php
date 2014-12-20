<?php

/**
 * The plugin controller.
 *
 * PHP version 5
 *
 * @category  CMSimple_XH
 * @package   Slideshow
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2012-2014 Christoph M. Becker <http://3-magi.net/>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */

/**
 * The plugin controller.
 *
 * @category CMSimple_XH
 * @package  Slideshow
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */
class Slideshow_Controller
{
    /**
     * Dispatches on plugin related requests.
     *
     * @return void
     */
    public static function dispatch()
    {
        if (XH_ADM) {
            if (self::isAdministrationRequested()) {
                self::handleAdministration();
            }
        }
    }

    /**
     * Returns the slideshow.
     *
     * @param string $path    The path of the image folder.
     * @param string $options The options in form of a query string.
     *
     * @return string (X)HTML.
     *
     * @global string The (X)HTML to insert to the end of the `body' element.
     * @global array  The paths of system files and folders.
     * @global array  The configuration of the plugins.
     *
     * @staticvar int $run The number of times the function has been called.
     */
    static function main($path, $options = '')
    {
        global $bjs, $pth, $plugin_cf;
        static $run = 0;

        $pcf = $plugin_cf['slideshow'];
        $validOpts = array(
            'order', 'effect', 'easing', 'delay', 'pause', 'duration'
        );
        $opts = self::getOpts($options, $validOpts);
        if (isset($bjs)) {
            $script =& $bjs;
        } else {
            $script =& $o; // FIXME: $o is not global!
        }
        $o = '';
        if (!$run) {
            $src = $pth['folder']['plugins'] . 'slideshow/slideshow.js';
            $script .= "<script type=\"text/javascript\" src=\"$src\"></script>";
        }
        $run++;
        $imgs = self::images($path, $opts['order']);
        list($w, $h) = getimagesize($imgs[0]);
        $id = "slideshow_$run";
        $style = "position:relative;width:100%;height:100%;overflow:hidden";
        $o .= "<div id=\"$id\" class=\"slideshow\" style=\"$style\">";
        foreach ($imgs as $i => $img) {
            $bn = basename($img);
            $bn = substr($bn, 0, strrpos($bn, '.'));
            $first = $i == 0 ? 'display:block;z-index:1' : 'display:none';
            $o .= tag(
                "img src=\"$img\" alt=\"$bn\""
                . " style=\"position:absolute;$first;width:100%\""
            );
        }
        $o .= '</div>';
        $script .= "<script type=\"text/javascript\">new slideshow.Show('$id'"
            . ",'$opts[effect]','$opts[easing]',$opts[delay],$opts[pause]"
            . ",$opts[duration]);</script>";
        return $o;
    }

    /**
     * Returns the options.
     *
     * Defaults are taken from $plugin_cf['slideshow']['default_*'].
     * Those will be overridden with the options in $query.
     *
     * @param string $query     The options given like a query string.
     * @param array  $validOpts The valid options.
     *
     * @return array
     *
     * @global array The configuration of the plugins.
     */
    protected static function getOpts($query, $validOpts)
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
     * Returns an instantiated view template.
     *
     * @param string $_template The path of the template file.
     * @param array  $_bag      The variables.
     *
     * @return string
     *
     * @global array The paths of system files and folders.
     * @global array The configuration of the core.
     */
    protected static function view($_template, $_bag)
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
     * Returns the list of image files in the given folder.
     *
     * @param string $path  The path of the folder.
     * @param string $order `fixed'/`sorted'/`random'.
     *
     * @return array
     *
     * @global array The paths of system files and folders.
     */
    protected static function images($path, $order)
    {
        global $pth;

        $imgs = array();
        $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
        $dh = opendir($path);
        while (($fn = readdir($dh)) !== false) {
            $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
            if (in_array($ext, array('gif', 'jpeg', 'jpg', 'png'))) {
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
                $imgs = array_merge(
                    array_slice($imgs, $n), array_slice($imgs, 0, $n)
                );
            }
        }
        return $imgs;
    }

    /**
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     */
    protected static function isAdministrationRequested()
    {
        global $slideshow;

        return isset($slideshow) && $slideshow == 'true';
    }

    /**
     * Handles the plugin administration.
     *
     * @return void
     *
     * @global string The value of the <var>admin</var> GP parameter.
     * @global string The value of the <var>action</var> GP parameter.
     * @global string The (X)HTML fragment to insert into the content area.
     */
    protected static function handleAdministration()
    {
        global $admin, $action, $o;

        $o .= print_plugin_admin('off');
        switch ($admin) {
        case '':
            $o .= self::info();
            break;
        default:
            $o .= plugin_admin_common($action, $admin, 'slideshow');
        }
    }

    /**
     * Returns the plugin information view.
     *
     * @return string (X)HTML.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the core.
     * @global array The localization of the plugins.
     */
    protected static function info()
    {
        global $pth, $tx, $plugin_tx;

        $ptx = $plugin_tx['slideshow'];
        $phpVersion = '5.0.0';
        foreach (array('ok', 'warn', 'fail') as $state) {
            $images[$state] = $pth['folder']['plugins'] . 'slideshow/images/'
                . $state . '.png';
        }
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = version_compare(PHP_VERSION, $phpVersion) >= 0 ? 'ok' : 'fail';
        foreach (array() as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = extension_loaded($ext) ? 'ok' : 'fail';
        }
        $checks[$ptx['syscheck_magic_quotes']]
            = !get_magic_quotes_runtime() ? 'ok' : 'fail';
        $checks[$ptx['syscheck_encoding']]
            = strtoupper($tx['meta']['codepage']) == 'UTF-8' ? 'ok' : 'warn';
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = is_writable($folder) ? 'ok' : 'warn';
        }
        $bag = array(
            'tx' => $ptx,
            'images' => $images,
            'checks' => $checks,
            'icon' => $pth['folder']['plugins'] . 'slideshow/slideshow.png',
            'version' => SLIDESHOW_VERSION
        );
        return self::view('info', $bag);
    }

}

?>
