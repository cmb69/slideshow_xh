<?php

/**
 * Copyright 2021 Christoph M. Becker
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

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;
use Plib\FakeRequest;
use Plib\View;

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
