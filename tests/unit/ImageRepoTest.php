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
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ImageRepoTest extends TestCase
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
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->foldername = vfsStream::url('test/');
        touch($this->foldername . 'foo.png');
        touch($this->foldername . 'foo.txt');
        touch($this->foldername . 'foo.jpeg');
        touch($this->foldername . 'foo.jpg');
        touch($this->foldername . 'foo.gif');
    }

    /**
     * Tests that four images are found.
     *
     * @return void
     */
    public function testFindsFourImages()
    {
        $images = (new ImageRepo)->findAll($this->foldername, 'fixed');
        $this->assertCount(4, $images);
        $this->assertContainsOnlyInstancesOf(Image::class, $images);
    }
}
