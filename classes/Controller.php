<?php

/**
 * The plugin controller.
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
 * The plugin controller.
 *
 * @category CMSimple_XH
 * @package  Slideshow
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */
class Controller
{
    /**
     * Dispatches on plugin related requests.
     *
     * @return void
     */
    public static function dispatch()
    {
        if (defined('XH_ADM') && XH_ADM) {
            if (function_exists('XH_registerStandardPluginMenuItems')) {
                XH_registerStandardPluginMenuItems(false);
            }
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
     * @global array  The localization of the plugins.
     *
     * @staticvar int $run The number of times the function has been called.
     */
    public static function main($path, $options = '')
    {
        global $bjs, $pth, $plugin_cf, $plugin_tx;
        static $run = 0;

        $opts = self::getOpts(
            $options,
            array('order', 'effect', 'easing', 'delay', 'pause', 'duration')
        );
        $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
        $current = isset($_COOKIE['slideshow_current'])
            ? $_COOKIE['slideshow_current']
            : false;
        $imgs = Image::findAll($path, $opts['order'], $current);
        if (count($imgs) < 2) {
            return XH_message('fail', $plugin_tx['slideshow']['message_insufficient_images'], $path);
        }
        if ($plugin_cf['slideshow']['cookie_use']) {
            setcookie('slideshow_current', basename($imgs[1]->getFilename()), 0, CMSIMPLE_URL);
        }
        if (!$run) {
            $config = array(
                'useCookie' => (bool) $plugin_cf['slideshow']['cookie_use']
            );
            $bjs .= '<script type="text/javascript">var slideshow = {config: '
                . json_encode($config) . '};</script>'
                . '<script type="text/javascript" src="'
                . $pth['folder']['plugins'] . 'slideshow/slideshow.js'
                . '"></script>';
        }
        $run++;
        $id = "slideshow_$run";
        $o = self::view('slideshow', array('id' => $id, 'imgs' => $imgs));
        $bjs .= "<script type=\"text/javascript\">new slideshow.Show('$id'"
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
     * Returns whether the plugin administration is requested.
     *
     * @return bool
     *
     * @global string Whether the plugin administration is requested.
     */
    protected static function isAdministrationRequested()
    {
        global $slideshow;

        return function_exists('XH_wantsPluginAdministration')
            && XH_wantsPluginAdministration('slideshow')
            || isset($slideshow) && $slideshow == 'true';
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
     * @global array The localization of the plugins.
     */
    protected static function info()
    {
        global $pth, $plugin_tx;

        $states = array(
            SystemChecker::OK => 'ok',
            SystemChecker::WARN => 'warn',
            SystemChecker::FAIL => 'fail'
        );
        foreach ($states as $state => $name) {
            $images[$state] = $pth['folder']['plugins'] . 'slideshow/images/'
                . $name . '.png';
        }
        $bag = array(
            'tx' => $plugin_tx['slideshow'],
            'images' => $images,
            'checks' => self::getSystemChecks(),
            'icon' => $pth['folder']['plugins'] . 'slideshow/slideshow.png',
            'version' => SLIDESHOW_VERSION
        );
        return self::view('info', $bag);
    }
    
    /**
     * Returns the system check results.
     *
     * @return array.
     *
     * @global array The paths of system files and folders.
     * @global array The localization of the plugins.
     */
    protected static function getSystemChecks()
    {
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['slideshow'];
        $phpVersion = '5.4.0';
        $xhVersion = '1.6';
        $checker = new SystemChecker();
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = $checker->checkPHPVersion($phpVersion);
        $checks[sprintf($ptx['syscheck_xhversion'], $xhVersion)]
            = $checker->checkXhVersion($xhVersion);
        foreach (array() as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = $checker->checkExtension($ext);
        }
        $checks[$ptx['syscheck_magic_quotes']]
            = $checker->checkMagicQuotes();
        $checks[$ptx['syscheck_encoding']]
            = $checker->checkEncoding();
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = $checker->checkWritability($folder);
        }
        return $checks;
    }
}
