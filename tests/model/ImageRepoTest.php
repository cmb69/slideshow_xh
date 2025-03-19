<?php

namespace Slideshow\Model;

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

    public function testFindsFixedImages(): void
    {
        $images = (new ImageRepo())->findAll($this->foldername, 'fixed');
        $this->assertEquals([
            new Image($this->foldername . "foo.gif"),
            new Image($this->foldername . "foo.jpeg"),
            new Image($this->foldername . "foo.jpg"),
            new Image($this->foldername . "foo.png"),
        ], $images);
    }

    public function testFindsSortedImages(): void
    {
        mt_srand(174);
        $images = (new ImageRepo())->findAll($this->foldername, 'sorted');
        $this->assertEquals([
            new Image($this->foldername . "foo.jpeg"),
            new Image($this->foldername . "foo.jpg"),
            new Image($this->foldername . "foo.png"),
            new Image($this->foldername . "foo.gif"),
        ], $images);
    }

    public function testFindsRandomImages(): void
    {
        mt_srand(174);
        $images = (new ImageRepo())->findAll($this->foldername, 'random');
        $this->assertEquals([
            new Image($this->foldername . "foo.gif"),
            new Image($this->foldername . "foo.png"),
            new Image($this->foldername . "foo.jpg"),
            new Image($this->foldername . "foo.jpeg"),
        ], $images);
    }
}
