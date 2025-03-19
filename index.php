<?php

use Plib\View;
use Slideshow\ImageRepo;
use Slideshow\MainCommand;
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
    global $pth, $plugin_cf, $plugin_tx;

    $view = new View($pth["folder"]["plugins"] . "slideshow/views/", $plugin_tx["slideshow"]);
    return (new MainCommand($plugin_cf["slideshow"], new ImageRepo(), $view))($path, $options);
}

Plugin::run();
