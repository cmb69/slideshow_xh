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

class View
{

    /** @var string */
    private $dir;

    /** @var string|null */
    private $template = null;

    /** @var array */
    private $data = [];

    public function __construct()
    {
        global $pth;

        $this->dir = "{$pth['folder']['plugins']}slideshow/views/";
    }

    /**
     * @param string $template
     * @return void
     */
    public function render($template, array $bag)
    {
        $this->template = "{$this->dir}$template.php";
        $this->data = $bag;
        unset($template, $bag);
        array_walk_recursive(
            $this->data,
            /** @param mixed $elt */
            function (&$elt) {
                $elt = XH_hsc($elt);
            }
        );
        extract($this->data);
        /** @psalm-suppress UnresolvableInclude */
        include $this->template;
    }
}
