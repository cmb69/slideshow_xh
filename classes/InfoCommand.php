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

class InfoCommand
{
    /** @var View */
    private $view;

    /** @var SystemChecker */
    private $systemChecker;

    public function __construct(View $view, SystemChecker $systemChecker)
    {
        $this->view = $view;
        $this->systemChecker = $systemChecker;
    }

    /**
     * @return void
     */
    public function __invoke()
    {
        $bag = array(
            'checks' => $this->getSystemChecks(),
            'version' => Plugin::VERSION
        );
        $this->view->render('info', $bag);
    }

    /**
     * @return array
     */
    private function getSystemChecks()
    {
        global $pth, $plugin_tx;

        $ptx = $plugin_tx['slideshow'];
        $phpVersion = '5.4.0';
        $xhVersion = '1.7.0';
        $checks = [[
            'class' => $this->systemChecker->checkPHPVersion($phpVersion),
            'message' => sprintf($ptx['syscheck_phpversion'], $phpVersion),
        ], [
            'class' => $this->systemChecker->checkXhVersion($xhVersion),
            'message' => sprintf($ptx['syscheck_xhversion'], $xhVersion),
        ]];
        $folders = [];
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[] = [
                'class' => $this->systemChecker->checkWritability($folder),
                'message' => sprintf($ptx['syscheck_writable'], $folder),
            ];
        }
        return $checks;
    }
}
