<?php

/**
 * Copyright (c) Christoph M. Becker
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

namespace Slideshow\Model;

class ImageRepo
{
    /**
     * @param string $order `fixed'/`sorted'/`random'
     * @return list<Image>
     */
    public function findAll(string $path, string $order): array
    {
        $images = array();
        if (is_dir($path) && $dir = opendir($path)) {
            while (($entry = readdir($dir)) !== false) {
                if (preg_match('/\.(gif|jpg|jpeg|png)$/i', $entry)) {
                    $webp = $path . preg_replace('/\.[^.]+$/', ".webp", $entry);
                    if (!is_file($webp)) {
                        $webp = null;
                    }
                    $images[] = new Image($path . $entry, $webp);
                }
            }
            closedir($dir);
        }
        return $this->sort($images, $order);
    }

    /**
     * @param list<Image> $images
     * @param string $order `fixed'/`sorted'/`random'
     * @return list<Image>
     */
    private function sort(array $images, string $order): array
    {
        if ($order == 'random') {
            shuffle($images);
        } else {
            usort($images, function (Image $image, Image $other) {
                return strnatcasecmp($image->getFilename(), $other->getFilename());
            });
            if ($order === 'sorted') {
                $n = rand(0, count($images) - 1);
                $images = array_merge(array_slice($images, $n), array_slice($images, 0, $n));
            }
        }
        return $images;
    }
}
