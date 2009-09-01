<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Setup error reporting
 */
error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

/**
 * Check PHP Version is compatible
 */
if (version_compare(PHP_VERSION, '5.2.3', 'ge') === false)
{
    throw new Exception(sprintf(
        'Incompatible PHP version detected. Expecting a version >= 5.2.3 but found version %s',
        PHP_VERSION
    ));
}

/**
 * DIRECTORY_SEPARATOR is kind of long; rename to a shorter constant
 */
if (defined('DS') === false)
{
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Define a few other handy constants
 */
if (defined('TAB') === false)
{
    define('TAB', "\t");
}

if (defined('NL') === false)
{
    define('NL', "\n");
}

/**
 * Load Willow
 */
if (defined('APPLICATION_PATH'))
{
    require APPLICATION_PATH . DS . '..' . DS . 'Vendor' . DS . 'Willow' . DS . 'Willow.php';
}
else
{
    require dirname(__FILE__) . DS . 'Willow.php';
}

/**
 * Setup autoloading
 */
require_once Willow::getRoot() . DS . 'Vendor' . DS . 'Willow' . DS . 'Willow' . DS . 'Autoloader' . DS . 'Abstract.php';
require_once Willow::getRoot() . DS . 'Vendor' . DS . 'Willow' . DS . 'Willow' . DS . 'Autoloader.php';
require_once Willow::getRoot() . DS . 'Vendor' . DS . 'Willow' . DS . 'Willow' . DS . 'Loader.php';
Willow_Autoloader::register(new Willow_Autoloader());

/**
 * Setup app config
 */
$config = Willow::getConfig();

Willow_Blackboard::register('config', $config);

/**
 * Magic Quotes === Evil
 */
if ((get_magic_quotes_gpc() === 1) || (get_magic_quotes_runtime() === 1))
{
    if ($config->app->allowMagicQuotes !== true)
    {
        throw new Willow_Exception(
            'Magic quotes are enabled, are NOT supported, and NEVER will be supported by Willow. If you dare to use them anyways, you must set app.allowMagicQuotes in your configuration as true.'
        );
    }
}

/**
 * Set default_charset to $config::charset
 */
ini_set('default_charset', $config->app->charset);

/**
 * Check for mb_string
 */
if (function_exists('mb_internal_encoding') === false)
{
    throw new Willow_Exception(
        'Required <a href="http://www.php.net/mb_string">Multibyte String Functions</a> missing'
    );
}

/**
 * Set mb_string encoding to config:app:charset
 */
mb_internal_encoding($config->app->charset);

/**
 * Setup timezone
 */
date_default_timezone_set($config->app->timezone);

/**
 * Setup utils
 */
Willow_Utils::register('string', 'Willow_Utils_String');
Willow_Utils::register('number', 'Willow_Utils_Number');

/**
 * Load framework plugins
 */
Willow_Plugin::loadAll(
    $exceptions = $config->plugins->disabled
);

/**
 * Remove bootrap vars from global scope
 */
unset($config);
