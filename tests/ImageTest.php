<?php

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
