<?php

/**
 * Copyright 2021 Christoph M. Becker
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

require_once './vendor/autoload.php';

require_once '../../cmsimple/functions.php';

require_once '../plib/classes/SystemChecker.php';
require_once '../plib/classes/View.php';
require_once '../plib/classes/FakeSystemChecker.php';

require_once './classes/Dic.php';
require_once './classes/Image.php';
require_once './classes/ImageRepo.php';
require_once './classes/MainCommand.php';
require_once './classes/InfoCommand.php';
require_once './classes/Plugin.php';

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.6";
