<?php

/**
 * Copyright 2012-2015 Christoph M. Becker
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

/**
 * The images.
 *
 * @category CMSimple_XH
 * @package  Slideshow
 * @author   Christoph M. Becker <cmbecker69@gmx.de>
 * @license  http://www.gnu.org/licenses/gpl-3.0.en.html GNU GPLv3
 * @link     http://3-magi.net/?CMSimple_XH/Slideshow_XH
 */
class Image
{
    /**
     * Returns the list of image files in the given folder.
     *
     * @param string $path    The path of the folder.
     * @param string $order   `fixed'/`sorted'/`random'.
     * @param string $current The basename of an image file.
     *
     * @return array
     */
    public static function findAll($path, $order, $current = false)
    {
        $images = array();
        if (is_dir($path) && $dir = opendir($path)) {
            while (($entry = readdir($dir)) !== false) {
                if (preg_match('/\.(gif|jpg|jpeg|png)$/i', $entry)) {
                    $images[] = new self($path . $entry);
                }
            }
            closedir($dir);
        }
        return self::sort($images, $order, $current);
    }

    /**
     * Sorts an array of images.
     *
     * @param array  $images  An array of images.
     * @param string $order   A sort order.
     * @param int    $current The basename of an image file.
     *
     * @return array
     */
    protected static function sort($images, $order, $current)
    {
        if ($order == 'random') {
            shuffle($images);
        } else {
            usort($images, array(get_class(), 'compareFilenames'));
            if ($order == 'sorted') {
                if ($current !== false) {
                    // if not found, take first image!
                    $n = 0;
                    foreach ($images as $i => $image) {
                        if (basename($image->getFilename()) == $current) {
                            $n = $i;
                            break;
                        }
                    }
                } else {
                    $n = rand(0, count($images) - 1);
                }
                $images = array_merge(array_slice($images, $n), array_slice($images, 0, $n));
            }
        }
        return $images;
    }

    /**
     * Compares two image filenames.
     *
     * @param Image $image An image.
     * @param Image $other Another image.
     *
     * @return int
     */
    protected static function compareFilenames($image, $other)
    {
        return strnatcasecmp($image->filename, $other->filename);
    }

    /**
     * The filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Initializes a new instance.
     *
     * @param string $filename A filename.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Returns the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Returns the name (i.e. the basename without the extension).
     *
     * @return string
     */
    public function getName()
    {
        return pathinfo($this->filename, PATHINFO_FILENAME);
    }
}
