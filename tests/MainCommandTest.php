<?php

namespace Slideshow;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;
use Slideshow\Model\Image;
use Slideshow\Model\ImageRepo;

class MainCommandTest extends TestCase
{
    public function testRendersView()
    {
        $imageRepo = $this->createStub(ImageRepo::class);
        $imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg"),
            new Image("pics/bar.jpg"),
        ]);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $this->view()
        );
        Approvals::verifyHtml($sut(new FakeRequest(), "pics")->output());
    }

    public function testShowsNothingIfTooFewImages()
    {
        $imageRepo = $this->createStub(ImageRepo::class);
        $imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg"),
        ]);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $this->view()
        );
        $this->assertSame("", $sut(new FakeRequest(), "pics")->output());
    }

    public function testWarnsAboutTooFewImagesIfAdmin()
    {
        $imageRepo = $this->createStub(ImageRepo::class);
        $imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg"),
        ]);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $this->view()
        );
        $this->assertStringContainsString(
            "The folder &quot;./userfiles/images/pics/&quot; has to contain two images at least!",
            $sut(new FakeRequest(["admin" => true]), "pics")->output()
        );
    }

    private function conf(): array
    {
        return XH_includeVar("./config/config.php", "plugin_cf")["slideshow"];
    }

    private function view(): View
    {
        return new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["slideshow"]);
    }
}
