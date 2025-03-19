<?php

/**
 * Copyright 2014-2021 Christoph M. Becker
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

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /**
     * The foldername.
     *
     * @var string
     */
    protected $foldername;

    /**
     * The test subject.
     *
     * @var Slideshow_Image
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Image('./foo/bar.jpg');
    }

    /**
     * Tests that it has the proper filename.
     *
     * @return void
     */
    public function testHasProperFilename()
    {
        $this->assertEquals('./foo/bar.jpg', $this->subject->getFilename());
    }

    /**
     * Tests the it has the proper name.
     *
     * @return void
     */
    public function testHasProperName()
    {
        $this->assertEquals('bar', $this->subject->getName());
    }
}
