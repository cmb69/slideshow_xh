<?php

use Slideshow\ImageRepo;
use Slideshow\MainCommand;
use Slideshow\Plugin;
use Slideshow\View;

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
    ob_start();
    (new MainCommand(new ImageRepo(), new View()))($path, $options);
    return ob_get_clean();
}

Plugin::run();
