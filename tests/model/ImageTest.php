<?php

namespace Slideshow\Model;

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    /** @var string */
    protected $foldername;

    /** @var Image */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Image('./foo/bar.jpg');
    }

    public function testHasProperFilename(): void
    {
        $this->assertEquals('./foo/bar.jpg', $this->subject->getFilename());
    }

    public function testHasProperName(): void
    {
        $this->assertEquals('bar', $this->subject->getName());
    }
}
