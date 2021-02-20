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

class Image
{
    /**
     * The filename.
     *
     * @var string
     */
    private $filename;

    /**
     * Initializes a new instance.
     *
     * @param string $filename A filename.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Returns the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Returns the name (i.e. the basename without the extension).
     *
     * @return string
     */
    public function getName()
    {
        return pathinfo($this->filename, PATHINFO_FILENAME);
    }
}
