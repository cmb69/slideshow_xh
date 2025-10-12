<?php

namespace Slideshow;

use ApprovalTests\Approvals;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\JavaScript;
use Plib\View;
use Slideshow\Model\Image;
use Slideshow\Model\ImageRepo;

class MainCommandTest extends TestCase
{
    /** @var array<string,string> */
    private $conf;

    /** @var ImageRepo&Stub */
    private $imageRepo;

    /** @var JavaScript&MockObject */
    private $javaScript;

    /** @var View */
    private $view;

    protected function setUp(): void
    {
        $this->conf = XH_includeVar("./config/config.php", "plugin_cf")["slideshow"];
        $this->imageRepo = $this->createStub(ImageRepo::class);
        $this->javaScript = $this->createMock(JavaScript::class);
        $this->view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["slideshow"]);
    }

    private function sut(): MainCommand
    {
        return new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf,
            $this->imageRepo,
            $this->javaScript,
            $this->view
        );
    }

    public function testRendersView()
    {
        $this->imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg", "pics/foo.webp"),
            new Image("pics/bar.jpg", null, "pics/bar.avif"),
        ]);
        Approvals::verifyHtml($this->sut()(new FakeRequest(), "pics")->output());
    }

    public function testShowsNothingIfTooFewImages()
    {
        $this->imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg"),
        ]);
        $this->assertSame("", $this->sut()(new FakeRequest(), "pics")->output());
    }

    public function testWarnsAboutTooFewImagesIfAdmin()
    {
        $this->imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg"),
        ]);
        $this->assertStringContainsString(
            "The folder &quot;./userfiles/images/pics/&quot; has to contain two images at least!",
            $this->sut()(new FakeRequest(["admin" => true]), "pics")->output()
        );
    }
}
