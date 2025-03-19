<?php

namespace Slideshow;

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

class ImageRepoTest extends TestCase
{
    /** @var string */
    protected $foldername;

    /** @var Image  */
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

    public function testFindsFourImages(): void
    {
        $images = (new ImageRepo())->findAll($this->foldername, 'fixed');
        $this->assertCount(4, $images);
        $this->assertContainsOnlyInstancesOf(Image::class, $images);
    }
}
