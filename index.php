<?php

use Plib\Request;
use Slideshow\Dic;

const SLIDESHOW_VERSION = "1.3";

/**
 * Returns the slideshow.
 *
 * @param string $path    The path of the image folder.
 * @param string $options The options in form of a query string.
 *
 * @return string (X)HTML.
 */
function slideshow($path, $options = '')
{
    return Dic::mainCommand()(Request::current(), $path, $options);
}
