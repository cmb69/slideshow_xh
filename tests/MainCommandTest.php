<?php

namespace Slideshow;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;
use Slideshow\Model\Image;
use Slideshow\Model\ImageRepo;
use Slideshow\Model\MetaRepo;

class MainCommandTest extends TestCase
{
    public function testRendersView()
    {
        $imageRepo = $this->createStub(ImageRepo::class);
        $imageRepo->method('findAll')->willReturn([
            new Image("pics/foo.jpg", "pics/foo.webp"),
            new Image("pics/bar.jpg", null, "pics/bar.avif"),
        ]);
        $metaRepo = $this->createStub(MetaRepo::class);
        $metaRepo->method("findAll")->willReturn([
            "foo" => "foo description",
            "bar" => "bar description",
        ]);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $metaRepo,
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
        $metaRepo = $this->createStub(MetaRepo::class);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $metaRepo,
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
        $metaRepo = $this->createStub(MetaRepo::class);
        $sut = new MainCommand(
            "./plugins/slideshow/",
            "./userfiles/images/",
            $this->conf(),
            $imageRepo,
            $metaRepo,
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
