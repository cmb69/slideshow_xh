<?php

namespace Slideshow;

use PHPUnit\Framework\TestCase;

class DicTest extends TestCase
{
    public function setUp(): void
    {
        global $pth, $plugin_cf, $plugin_tx;

        $pth = ["folder" => ["images" => "", "plugins" => ""]];
        $plugin_cf = ["slideshow" => []];
        $plugin_tx = ["slideshow" => []];
    }

    public function testMakesMainCommand(): void
    {
        $this->assertInstanceOf(MainCommand::class, Dic::mainCommand());
    }

    public function testMakesInfoCommand(): void
    {
        $this->assertInstanceOf(InfoCommand::class, Dic::infoCommand());
    }
}
