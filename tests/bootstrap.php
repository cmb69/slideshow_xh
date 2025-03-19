<?php

require_once './vendor/autoload.php';

require_once '../../cmsimple/functions.php';

require_once '../plib/classes/Request.php';
require_once '../plib/classes/Response.php';
require_once '../plib/classes/SystemChecker.php';
require_once '../plib/classes/Url.php';
require_once '../plib/classes/View.php';
require_once '../plib/classes/FakeRequest.php';
require_once '../plib/classes/FakeSystemChecker.php';

require_once './classes/model/Image.php';
require_once './classes/model/ImageRepo.php';
require_once './classes/Dic.php';
require_once './classes/MainCommand.php';
require_once './classes/InfoCommand.php';

const CMSIMPLE_XH_VERSION = "CMSimple_XH 1.7.6";
const SLIDESHOW_VERSION = "1.4-dev";
