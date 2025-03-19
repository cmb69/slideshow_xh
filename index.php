<?php

use Slideshow\Dic;
use Slideshow\Plugin;

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
    return Dic::mainCommand()($path, $options);
}

Plugin::run();
