<?php

/**
 * Copyright 2012-2021 Christoph M. Becker
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

class ImageRepo
{
    /**
     * Returns the list of image files in the given folder.
     *
     * @param string $path    The path of the folder.
     * @param string $order   `fixed'/`sorted'/`random'.
     *
     * @return array
     */
    public function findAll($path, $order)
    {
        $images = array();
        if (is_dir($path) && $dir = opendir($path)) {
            while (($entry = readdir($dir)) !== false) {
                if (preg_match('/\.(gif|jpg|jpeg|png)$/i', $entry)) {
                    $images[] = new Image($path . $entry);
                }
            }
            closedir($dir);
        }
        return $this->sort($images, $order);
    }

    /**
     * Sorts an array of images.
     *
     * @param array  $images  An array of images.
     * @param string $order   A sort order.
     *
     * @return array
     */
    private function sort($images, $order)
    {
        if ($order == 'random') {
            shuffle($images);
        } else {
            usort($images, [$this, 'compareFilenames']);
            if ($order === 'sorted') {
                $n = rand(0, count($images) - 1);
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
    protected function compareFilenames($image, $other)
    {
        return strnatcasecmp($image->getFilename(), $other->getFilename());
    }
}
