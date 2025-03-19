<?php

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
