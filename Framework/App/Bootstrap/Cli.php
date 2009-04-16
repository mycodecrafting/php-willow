<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Define APPLICATION_PATH
 */
define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

/**
 * Bootstrap Willow framework
 */
require APPLICATION_PATH . '/../Vendor/Willow/Bootstrap.php';

/**
 * Load framework plugins
 */
Willow_Plugin::loadAll(
    $exceptions = Willow_Blackboard::get('config')->plugins->disabled
);

/**
 * Register a fallback autoloader
 */
Willow_Autoloader::register(new Willow_Autoloader_Fallback());

/**
 * Cleanup bootstrap vars
 */
unset($exceptions);
