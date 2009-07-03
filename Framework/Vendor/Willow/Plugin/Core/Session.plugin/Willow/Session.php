<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Session implements Willow_Session_Namespace_Handler_Interface
{

    protected function __construct()
    {
    }

    /**
     * ...
     */
    protected static $_config = null;

    /**
     * ...
     */
    public static function getConfig()
    {
        return self::$_config;
    }

    /**
     * ...
     */
    public static function setConfig(Willow_Yaml_Node $config)
    {
        self::$_config = $config;
    }

    /**
     * ...
     */
    protected static $_requireCookies = false;

    /**
     * ...
     */
    public static function requireCookies($required = true)
    {
        self::$_requireCookies = $required;
    }

    /**
     * ...
     */
    protected static $_isStarted = false;

    /**
     * ...
     */
    public static function start()
    {
        /**
         * No handler registered
         */
        if (self::getHandler() === null)
        {
            throw new Willow_Session_Exception(
                'Must register a session handler with Willow_Session::registerHandler() ' .
                'prior to calling Willow_Session::start()'
            );
        }

        /**
         * Session was already started
         */
        if (self::$_isStarted === true)
        {
            return;
        }

        /**
         * Session was auto-started
         */
        if (defined('SID'))
        {
            throw new Willow_Session_Exception(
                'Session has already been started by session.auto-start or session_start()'
            );
        }

        /**
         * Grab config from Willow if not passed in
         */
        if (self::getConfig() === null)
        {
            self::setConfig(Willow_Blackboard::get('config')->session);
        }

        /**
         * Cookies are required; set a test cookie
         */
        if (self::$_requireCookies === true)
        {
			ini_set('session.use_only_cookies', '1');

            Willow::utils()->cookie('wf_test_cookie')->setValue('1');

            /**
             * check if test cookie exists  
             */
            if (Willow::utils()->cookie('wf_test_cookie')->getValue() === '1')
            {
                self::_start();
            }
        }

        /**
         * Cookies are not required; check for known bots and do not start a session for them
         */
        else
        {
            /**
             * Client is not a known spider/bot; go ahead and start
             */
            if (self::isSpider() === false)
            {
                self::_start();
            }
        }

        /**
         * Session was not started
         */
        if (self::$_isStarted === false)
        {
            return;
        }

        /**
         * Setup special session variable scope(s)
         */
        if (!isset($_SESSION['__WF']))
        {
            $_SESSION['__WF'] = array();
        }

        /**
         * Setup namescape scope
         */
        if (!isset($_SESSION['__WF']['__NAMESPACES']))
        {
            $_SESSION['__WF']['__NAMESPACES'] = array();
        }

        /**
         * Prevent session fixation
         */
        if (self::getConfig()->security->fixation === true)
        {
            self::_preventFixation();
        }

        /**
         * Prevent session hi-jacking with browser fingerprinting
         */
        if (self::getConfig()->security->fingerprint === true)
        {
            self::_checkFingerprint();
        }
    }

    /**
     * ...
     */
    protected static function _start()
    {
        session_start();
        self::$_isStarted = true;
    }

    /**
     * ...
     */
    public static function isStarted()
    {
        return self::$_isStarted;
    }

    /**
     * ...
     */
    public static function getId()
    {
        return session_id();
    }

    /**
     * ...
     */
    public static function getName()
    {
        return session_name();
    }

    /**
     * ...
     */
    public static function writeClose()
    {
        session_write_close();
    }

    /**
     * ...
     */
    public static function destroy($expireCookie = true)
    {
        /**
         * Auto start session if it's not yet started
         */
        if (self::isStarted() === false)
        {
            self::start();
        }

        /**
         * Destroy session
         */
        session_destroy();

        /**
         * Expire session cookie
         */
        if ($expireCookie === true)
        {
            self::expireCookie();
        }
    }

    /**
     * ...
     */
    public static function expireCookie()
    {
        if (isset($_COOKIE[self::getName()]))
        {
            $params = session_get_cookie_params();
            setcookie(
                self::getName(),
                false,
                time() - 3600,
                $params['path'],
                $params['domain'],
                $params['secure']
            );
        }
    }

    /**
     * ...
     */
    public static function regenerateId()
    {
        if (self::isStarted() === false)
        {
            return;
        }

        /**
         * Generate new session id
         */
        session_regenerate_id(true);

        /**
         * Session id has changed; generate new fingerprint if needed
         */
        if (self::getConfig()->security->fingerprint === true)
        {
            $security = new Willow_Session_Security_Fingerprint();
            $security->resetFingerprint();
        }
    }

    /**
     * ...
     */
    protected static $_isSpider = null;

    /**
     * ...
     */
    public static function isSpider()
    {
        /**
         * Unknown spider state; figure it out!
         */
        if (self::$_isSpider === null)
        {
            $user_agent = trim(strtolower(getenv('HTTP_USER_AGENT')));
            $isSpider = false;

            if ($user_agent !== '')
            {
                foreach (Willow_Blackboard::get('config')->session->spiders as $spider)
                {
                    if (strpos($user_agent, $spider) !== false)
                    {
                        $isSpider = true;
                        break;
                    }
                }
            }

            self::$_isSpider = $isSpider;
        }

        /**
         * Return value
         */
        return self::$_isSpider;
    }

    /**
     * ...
     */
    protected static $_namespaces = array();

    /**
     * ...
     */
    public static function getNamespace($namespace)
    {
        /**
         * Auto start session if it's not yet started
         */
        if (self::isStarted() === false)
        {
            self::start();
        }

        /**
         * If namespace does not exist, create it
         */
        if (self::hasNamespace($namespace) === false)
        {
            return self::createNamespace($namespace);
        }

        /**
         * Return existing namespace
         */
        return self::$_namespaces[$namespace];
    }

    /**
     * Create a namespace within the session
     *
     * @param string $namespace
     * @return Willow_Session_Namespace
     * @throws Willow_Session_Exception When namespace has already been created
     */
    public static function createNamespace($namespace)
    {
        /**
         * Auto start session if it's not yet started
         */
        if (self::isStarted() === false)
        {
            self::start();
        }

        /**
         * Cannot create an existing namespace
         */
        if (self::hasNamespace($namespace) === true)
        {
            throw new Willow_Session_Exception(sprintf(
                'Cannot create existing namespace "%s". Use Willow_Session::getNamespace() to access.',
                $namespace
            ));
        }

        /**
         * Create new namespace
         */
        self::$_namespaces[$namespace] = new Willow_Session_Namespace($namespace);

        /**
         * Return namespace
         */
        return self::$_namespaces[$namespace];
    }

    /**
     * Check if a namespace has been created
     *
     * @return boolean
     */
    public static function hasNamespace($namespace)
    {
        return array_key_exists($namespace, self::$_namespaces);
    }

    /**
     * @var Willow_Session_Handler_Interface
     */
    protected static $_handler = null;

    /**
     * ...
     */
    public static function registerHandler(Willow_Session_Handler_Interface $handler)
    {
        session_set_save_handler(
            array(&$handler, 'open'),
            array(&$handler, 'close'),
            array(&$handler, 'read'),
            array(&$handler, 'write'),
            array(&$handler, 'destroy'),
            array(&$handler, 'gc')
        );

        self::$_handler = $handler;
    }

    /**
     * ...
     */
    public static function getHandler()
    {
        return self::$_handler;
    }

    /**
     * ...
     */
    protected static function _preventFixation()
    {
        $security = new Willow_Session_Security_Fixation();
        $security->takeMeasures();
    }

    /**
     * Prevent session hi-jacking with browser fingerprinting
     */
    protected static function _checkFingerprint()
    {
        $security = new Willow_Session_Security_Fingerprint();
        $security->takeMeasures();
    }

}
