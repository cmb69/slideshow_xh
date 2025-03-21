<?php

/**
 * Copyright (c) Christoph M. Becker
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

namespace Slideshow\Model;

class Image
{
    /** @var string */
    private $filename;

    /** @var ?string */
    private $webp;

    /** @var ?string */
    private $avif;

    public function __construct(string $filename, ?string $webp = null, ?string $avif = null)
    {
        $this->filename = $filename;
        $this->webp = $webp;
        $this->avif = $avif;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function hasWebp(): bool
    {
        return $this->webp !== null;
    }

    public function getWebp(): string
    {
        assert($this->webp !== null);
        return $this->webp;
    }

    public function hasAvif(): bool
    {
        return $this->avif !== null;
    }

    public function getAvif(): string
    {
        assert($this->avif !== null);
        return $this->avif;
    }

    public function getName(): string
    {
        $name = pathinfo($this->filename, PATHINFO_FILENAME);
        return $name;
    }
}
