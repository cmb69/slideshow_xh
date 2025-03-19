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
use Plib\FakeSystemChecker;
use Plib\View;

class InfoCommandTest extends TestCase
{
    public function testRendersView()
    {
        $view = new View("./views/", XH_includeVar("./languages/en.php", "plugin_tx")["slideshow"]);
        $sut = new InfoCommand("./plugins/slideshow/", $view, new FakeSystemChecker());
        $response = $sut();
        $this->assertSame("Slideshow 1.4-dev", $response->title());
        Approvals::verifyHtml($response->output());
    }
}
