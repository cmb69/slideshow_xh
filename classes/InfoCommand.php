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

class InfoCommand extends Command
{
    /**
     * @return void
     */
    public function __invoke()
    {
        global $pth, $plugin_tx;

        $states = array(
            SystemChecker::OK => 'ok',
            SystemChecker::WARN => 'warn',
            SystemChecker::FAIL => 'fail'
        );
        foreach ($states as $state => $name) {
            $images[$state] = $pth['folder']['plugins'] . 'slideshow/images/'
                . $name . '.png';
        }
        $bag = array(
            'tx' => $plugin_tx['slideshow'],
            'images' => $images,
            'checks' => $this->getSystemChecks(),
            'version' => SLIDESHOW_VERSION
        );
        $this->view('info', $bag);
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
        $checker = new SystemChecker();
        $checks = array();
        $checks[sprintf($ptx['syscheck_phpversion'], $phpVersion)]
            = $checker->checkPHPVersion($phpVersion);
        $checks[sprintf($ptx['syscheck_xhversion'], $xhVersion)]
            = $checker->checkXhVersion($xhVersion);
        foreach (array() as $ext) {
            $checks[sprintf($ptx['syscheck_extension'], $ext)]
                = $checker->checkExtension($ext);
        }
        foreach (array('config/', 'languages/') as $folder) {
            $folders[] = $pth['folder']['plugins'] . 'slideshow/' . $folder;
        }
        foreach ($folders as $folder) {
            $checks[sprintf($ptx['syscheck_writable'], $folder)]
                = $checker->checkWritability($folder);
        }
        return $checks;
    }
}
