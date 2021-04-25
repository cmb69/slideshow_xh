<?php

const CMSIMPLE_XH_VERSION = "1.7.4";

/**
 * @param string $string
 * @return string
 */
function XH_hsc($string) {}

/**
 * @return string
 */
function plugin_admin_common() {}

/**
 * @param string $main
 * @return string HTML
 */
function print_plugin_admin($main) {}

/**
 * @param string $type
 * @param string $message
 * @param mixed ...$args
 * @return string
 */
function XH_message($type, $message, ...$args) {}

/**
 * @param bool $showMain
 * @return void
 */
function XH_registerStandardPluginMenuItems($showMain) {}

/**
 * @param string $pluginName
 * @return bool
 */
function XH_wantsPluginAdministration($pluginName) {}
