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

class MainCommand
{
    /**
     * @param string $path
     * @param string $options
     * @return void
     */
    public function __invoke($path, $options = '')
    {
        global $bjs, $pth, $plugin_tx;
        static $run = 0;

        $opts = $this->getOptions($options);
        $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
        $imgs = Image::findAll($path, $opts['order']);
        if (count($imgs) < 2) {
            return XH_message('fail', $plugin_tx['slideshow']['message_insufficient_images'], $path);
        }
        $imgs = array_map(
            function ($img) {
                static $first = true;
                $res = [
                    'filename' => $img->getFilename(),
                    'name' => $img->getName(),
                    'style' => $first
                        ? "position: static; display: block; z-index: 1; width: 100%"
                        : "position: absolute; display: none; width: 100%",
                ];
                $first = false;
                return $res;
            },
            $imgs
        );
        if (!$run) {
            $bjs .= '<script src="'
                . $pth['folder']['plugins'] . 'slideshow/slideshow.min.js'
                . '"></script>';
        }
        $run++;
        $id = $run;
        (new View)->render('slideshow', compact('id', 'imgs', 'opts'));
    }

    /**
     * @param string $query
     * @return array
     */
    private function getOptions($query)
    {
        global $plugin_cf;

        $validOpts = ['order', 'effect', 'easing', 'delay', 'pause', 'duration'];

        $query = html_entity_decode($query, ENT_QUOTES|ENT_HTML5, 'UTF-8');
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
