<?php

/**
 * Testing the image class.
 *
 * PHP version 5
 *
 * @category  Testing
 * @package   Slideshow
 * @author    Christoph M. Becker <cmbecker69@gmx.de>
 * @copyright 2014-2015 Christoph M. Becker <http://3-magi.net>
 * @license   http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link      http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */

namespace Slideshow;

require_once './vendor/autoload.php';
require_once './classes/required_classes.php';

use PHPUnit_Framework_TestCase;
use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStream;

/**
 * A test case to for the image class.
 *
 * @category Testing
 * @package  Slideshow
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */
class ImageTest extends PHPUnit_Framework_TestCase
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

    /**
     * Sets up the test fixture.
     *
     * @return void
     */
    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('test'));
        $this->foldername = vfsStream::url('test/');
        touch($this->foldername . 'foo.png');
        touch($this->foldername . 'foo.txt');
        touch($this->foldername . 'foo.jpeg');
        touch($this->foldername . 'foo.jpg');
        touch($this->foldername . 'foo.gif');
        $this->subject = new Image('./foo/bar.jpg');
    }

    /**
     * Tests that four images are found.
     *
     * @return void
     */
    public function testFindsFourImages()
    {
        $images = Image::findAll($this->foldername, 'fixed');
        $this->assertCount(4, $images);
        $this->assertContainsOnlyInstancesOf('Slideshow\\Image', $images);
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
