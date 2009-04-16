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
 * Register accepted protocols
 */
Willow_Request::registerProtocols('http', 'json');

/**
 * Register aliases for HTTP protocol
 */
Willow_Request::registerProtocolAlias('html', 'http');
Willow_Request::registerProtocolAlias('htm', 'http');
Willow_Request::registerProtocolAlias('php', 'http');
Willow_Request::registerProtocolAlias('phtml', 'http');
Willow_Request::registerProtocolAlias('do', 'http');

/**
 * Get an instance of the application
 */
$app = Willow_Application::getInstance();

/**
 * Setup the request in the application
 */
$app->setRequest(new Willow_Request());

/**
 * Setup the router (we will use the rewrite router and routes from config)
 */
$app->setRouter(new Willow_Request_Router_Rewrite(
    $routes = Willow_Blackboard::get('config')->routes
));

/**
 * Register a fallback autoloader
 */
Willow_Autoloader::register(new Willow_Autoloader_Fallback());

/**
 * Cleanup bootstrap vars
 */
unset($exceptions, $app, $routes);
