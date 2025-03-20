<?php

use Plib\Request;
use Slideshow\Dic;

const SLIDESHOW_VERSION = "1.4";

function slideshow(string $path, string $options = ''): string
{
    return Dic::mainCommand()(Request::current(), $path, $options)();
}
