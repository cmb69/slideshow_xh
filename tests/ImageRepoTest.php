<?php

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
        $images = (new ImageRepo())->findAll($this->foldername, 'fixed');
        $this->assertCount(4, $images);
        $this->assertContainsOnlyInstancesOf(Image::class, $images);
    }
}
