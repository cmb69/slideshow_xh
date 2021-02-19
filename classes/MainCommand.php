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

class MainCommand extends Command
{
    /**
     * @param string $path
     * @param string $options
     * @return void
     */
    public function __invoke($path, $options = '')
    {
        global $bjs, $pth, $plugin_cf, $plugin_tx;
        static $run = 0;

        $opts = $this->getOpts(
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
            $bjs .= '<script>var slideshow = {config: '
                . json_encode($config) . '};</script>'
                . '<script src="'
                . $pth['folder']['plugins'] . 'slideshow/slideshow.min.js'
                . '"></script>';
        }
        $run++;
        $id = "slideshow_$run";
        $bag = [
            'id' => $id,
            'imgs' => $imgs,
            'imagestyle' => function ($index) {
                if ($index === 0) {
                    return "position: static; display: block; z-index: 1; width: 100%";
                }
                return "position: absolute; display: none; width: 100%";
            },
        ];
        $this->view('slideshow', $bag);
        $bjs .= "<script>new slideshow.Show('$id'"
            . ",'$opts[effect]','$opts[easing]',$opts[delay],$opts[pause]"
            . ",$opts[duration]);</script>";
    }

    /**
     * @param string $query
     * @return array
     */
    private function getOpts($query, array $validOpts)
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
}
