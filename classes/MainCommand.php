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
    /** @var View */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param string $path
     * @param string $options
     * @return void
     */
    public function __invoke($path, $options = '')
    {
        global $pth, $plugin_tx;

        $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
        $imgs = (new ImageRepo)->findAll($path, $opts['order']);
        if (count($imgs) < 2) {
            return XH_message('fail', $plugin_tx['slideshow']['message_insufficient_images'], $path);
        }
        $this->includeJsOnce();
        $this->view->render(
            'slideshow',
            [
                'id' => uniqid(),
                'imgs' => $this->getImageData($imgs),
                'opts' => $this->getOptions($options),
            ]
        );
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

    /**
     * @param Image[] $images
     * @return array
     */
    private function getImageData(array $images)
    {
        $res = [];
        foreach ($images as $i => $image) {
            $res[] = [
                'filename' => $image->getFilename(),
                'name' => $image->getName(),
                'style' => $i === 0
                    ? "position: static; display: block; z-index: 1; width: 100%"
                    : "position: absolute; display: none; width: 100%",
            ];
        }
        return $res;
    }

    /** @return void */
    private function includeJsOnce()
    {
        global $bjs, $pth;
        static $run = false;

        if ($run) {
            return;
        }
        $bjs .= "<script src=\"{$pth['folder']['plugins']}slideshow/slideshow.min.js\"></script>";
        $run = true;
    }
}
