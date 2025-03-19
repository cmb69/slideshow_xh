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

use Plib\View;

class MainCommand
{
    /** @var array<string,string> */
    private $conf;

    /** @var ImageRepo */
    private $imageRepo;

    /** @var View */
    private $view;

    /** @param array<string,string> $conf */
    public function __construct(array $conf, ImageRepo $imageRepo, View $view)
    {
        $this->conf = $conf;
        $this->imageRepo = $imageRepo;
        $this->view = $view;
    }

    /**
     * @param string $path
     * @param string $options
     */
    public function __invoke($path, $options = ''): string
    {
        global $pth;

        $opts = $this->getOptions($options);
        $path = $pth['folder']['images'] . rtrim($path, '/') . '/';
        $imgs = $this->imageRepo->findAll($path, $opts['order']);
        if (count($imgs) < 2) {
            if (XH_ADM) { // @phpstan-ignore-line
                return $this->view->message("fail", "message_insufficient_images", $path);
            }
            return "";
        }
        $this->includeJsOnce();
        $styles = $loading = [];
        foreach ($imgs as $i => $img) {
            $styles[] = $i === 0
                ? "position: static; display: block; z-index: 1; width: 100%"
                : "position: absolute; display: none; width: 100%";
            $loading[] = $i === 0 ? "eager" : "lazy";
        }
        return $this->view->render('slideshow', [
            'imgs' => $imgs,
            'styles' => $styles,
            'loading' => $loading,
            'opts' => $opts,
        ]);
    }

    /**
     * @param string $query
     * @return array<string,string>
     */
    private function getOptions($query)
    {
        $validOpts = ['order', 'effect', 'easing', 'delay', 'pause', 'duration'];

        $query = html_entity_decode($query, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        parse_str($query, $opts);

        $res = array();
        foreach ($validOpts as $key) {
            $res[$key] = isset($opts[$key]) && is_string($opts[$key]) && $opts[$key] !== ""
                ? $opts[$key]
                : $this->conf["default_$key"];
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
