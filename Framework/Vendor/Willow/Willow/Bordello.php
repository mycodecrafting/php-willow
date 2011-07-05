<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * An service provider
 */
class Willow_Bordello implements Willow_Registry_Interface
{

    /**
     * @var array Registered providers
     */
    private $_registered = array();

    /**
     * Prevent public construction of object
     */
    private function __construct()
    {
    }

    /**
     * Retrieve registered provider belonging to $key
     *
     * @param string $key
     * @return object
     */
    public static function get($key)
    {
        return self::_instance()->$key;
    }

    /**
     * Register a provider to $key
     *
     * @param string $key
     * @param object $object
     * @param boolean $readOnly Do not allow re-registration of this key?
     * @throws Willow_Blackboard_Exception When $object is not an object
     * @return Willow_Bordello Current Willow_Blackboard instance
     */
    public static function register($key, $class, $readOnly = false)
    {
        if (is_string($class) === false)
        {
            throw new Willow_Bordello_Exception(
                'Can only register a class name with the bordello'
            );
        }

        self::_instance()->_registered[$key] = $class;
        return self::_instance();
    }

    /**
     * Check if a class has been registered to $key
     *
     * @param string $key
     * @return boolean
     */
    public static function isRegistered($key)
    {
        return isset(self::_instance()->$key);
    }

    /**
     * Erase all registered objects
     */
    public static function erase()
    {
        self::$instance = null;
    }

    /**
     * @var Willow_Blackboard
     */
    private static $_instance = null;

    /**
     * Retrieve current Willow_Bordello instance
     *
     * @return Willow_Bordello
     */
    private static function _instance()
    {
        if ((self::$_instance instanceof Willow_Bordello) === false)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Retrieve registered class belonging to $key
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {
        if (self::isRegistered($key) === false)
        {
            return $key;
        }

        return self::_instance()->_registered[$key];
    }

    /**
     * Check if an object has been registered to $key
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return array_key_exists($key, self::_instance()->_registered);
    }

}
