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

namespace Slideshow;

use Plib\Response;
use Plib\SystemChecker;
use Plib\View;

class InfoCommand
{
    /** @var string */
    private $pluginFolder;

    /** @var View */
    private $view;

    /** @var SystemChecker */
    private $systemChecker;

    public function __construct(string $pluginFolder, View $view, SystemChecker $systemChecker)
    {
        $this->pluginFolder = $pluginFolder;
        $this->view = $view;
        $this->systemChecker = $systemChecker;
    }

    public function __invoke(): Response
    {
        $bag = array(
            'checks' => $this->getSystemChecks(),
            'version' => SLIDESHOW_VERSION
        );
        return Response::create($this->view->render('info', $bag))
            ->withTitle($this->view->esc("Slideshow " . SLIDESHOW_VERSION));
    }

    /** @return list<array{class:string,message:string}> */
    private function getSystemChecks(): array
    {
        $phpVersion = '7.1.0';
        $checks = [];
        $checks[] = [
            'class' => $this->systemChecker->checkVersion(PHP_VERSION, $phpVersion) ? "xh_success" : "xh_fail",
            'message' => $this->view->text("syscheck_phpversion", $phpVersion),
        ];
        $xhVersion = '1.7.0';
        $checks[] = [
            'class' => $this->systemChecker->checkVersion(CMSIMPLE_XH_VERSION, "CMSimple_XH $xhVersion")
                ? "xh_success"
                : "xh_fail",
            'message' => $this->view->text("syscheck_xhversion", $xhVersion),
        ];
        $plibVersion = "1.2";
        $checks[] = [
            'class' => $this->systemChecker->checkPlugin("plib", $plibVersion) ? "xh_success" : "xh_fail",
            'message' => $this->view->text("syscheck_plibversion", $plibVersion),
        ];
        $folders = [];
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $this->pluginFolder . $folder;
        }
        foreach ($folders as $folder) {
            $checks[] = [
                'class' => $this->systemChecker->checkWritability($folder) ? "xh_success" : "xh_warning",
                'message' => $this->view->text("syscheck_writable", $folder),
            ];
        }
        return $checks;
    }
}
