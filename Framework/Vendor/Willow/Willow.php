<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Before adding functionality to this class, careful consideration should be given 
 * as to if it should exist here or in seperate class.  In most cases, the later will
 * be true.
 */
final class Willow
{

    /**
     * @var array Alias lookup cache
     */
    private $_aliases = array();

    /**
     * Prevent public construction of object
     */
    private function __construct()
    {
    }

    /**
     * @var string Current app deployment directory name
     */
    private $_deployment = null;

    /**
     * Get the current app deployment directory name
     *
     * @return string Deployment directory name
     */
    public static function getDeployment()
    {
        if (self::_instance()->_deployment === null)
        {
            $domainTree = explode('.', $_SERVER['HTTP_HOST']);

            $deploymentRoot = self::getRoot() . DIRECTORY_SEPARATOR . 'Deployment';

            do
            {
                $deployment = implode('.', $domainTree);

                if (is_dir($deploymentRoot . DIRECTORY_SEPARATOR . $deployment))
                {
                    self::_instance()->_deployment = $deployment;
                    break;
                }
            }
            while (array_shift($domainTree) !== null);

            if (self::_instance()->_deployment === null)
            {
                $deployment = 'default';
            }
        }

        return self::_instance()->_deployment;
    }

    /**
     * @var string Current app code root directory full path
     */
    private $_root = null;

    /**
     * ...
     */
    public static function getRoot()
    {
        if (self::_instance()->_root === null)
        {
            self::_instance()->_root = realpath(dirname(__FILE__) . '/../../');
        }

        return self::_instance()->_root;
    }

    /**
     * @var Willow_Yaml_Node Application config
     */
    private $_config;

    /**
     * Get the application config
     *
     * @return Willow_Yaml_Node
     */
    public static function getConfig()
    {
        if ((self::_instance()->_config instanceof Willow_Yaml_Node) === false)
        {
            self::_instance()->_config = self::_buildConfig();
        }

        return self::_instance()->_config;
    }

    /**
     * Build the application config
     *
     * @return Willow_Yaml_Node
     */
    private static function _buildConfig()
    {
        /**
         * Start with config in core framework
         */
        $conf = new Willow_Yaml_Node(
            Willow_Loader::getRealPath('Core:Config:Willow', false, 'yml')
        );

        /**
         * Assimilate app config
         */
        if (($yaml = Willow_Loader::getRealPath('App:Config:Willow', false, 'yml')) !== false)
        {
            $conf->assimilate($yaml);
        }

        /**
         * Assimilate deployment config
         */
        if (($yaml = Willow_Loader::getRealPath('Deployment:Config:Willow', false, 'yml')) !== false)
        {
            $conf->assimilate($yaml);
        }

        /**
         * Create routes node if it does not exist
         */
        if (($routes = $conf->routes) === null)
        {
            $routes = $conf->addChildNode('routes');
        }

        /**
         * Add app routes
         */
        if (($yaml = Willow_Loader::getRealPath('App:Config:Routes', false, 'yml')) !== false)
        {
            $routes->assimilate($yaml);
        }

        /**
         * Add deployment routes
         */
        if (($yaml = Willow_Loader::getRealPath('Deployment:Config:Routes', false, 'yml')) !== false)
        {
            $routes->assimilate($yaml);
        }

        return $conf;
    }

    /**
     * @var WillowUtils
     */
    private $_utils = null;

    /**
     * Shorthand access to utility classes
     */

 // Willow::utils()->string('Yes')->autoType()
 // Willow::utils()->string('string')->operation1->operation2->operation3()
 // Willow::utils()->password()->generate(8)
 // Willow::utils()->password($password)->encrypt()
 // Willow::utils()->password($password)->validate($encrypted)

/**
 * move to Willow_Utils ?  __callStatic() not available until PHP 5.3.0
 *
 * Willow_Utils::string('Yes')->autoType();
 */

    public static function utils()
    {
        if ((self::_instance()->_utils instanceof Willow_Utils) === false)
        {
            self::_instance()->_utils = new Willow_Utils();
        }

        return self::_instance()->_utils;
    }

    /**
     * Default bootstrap framework routine
     *
     * @return void
     */
    public static function bootstrap()
    {


    }

    /**
     * @var Willow Instance of self
     */
    private static $_instance = null;

    /**
     * Get the singleton instance of self
     */
    private static function _instance()
    {
        if ((self::$_instance instanceof Willow) === false)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}
