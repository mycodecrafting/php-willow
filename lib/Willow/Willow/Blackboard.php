<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * An object registry
 */
class Willow_Blackboard implements Willow_Registry_Interface
{

    /**
     * @var array Registered objects
     */
    private $_registered = array();

    /**
     * Prevent public construction of object
     */
    private function __construct()
    {
    }

    /**
     * Retrieve registered object belonging to $key
     *
     * @param string $key
     * @return object
     */
    public static function get($key)
    {
        return self::_instance()->$key;
    }

    /**
     * Register an object to $key
     *
     * @param string $key
     * @param object $object
     * @param boolean $readOnly Do not allow re-registration of this key?
     * @throws Willow_Blackboard_Exception When $object is not an object
     * @return Willow_Blackboard Current Willow_Blackboard instance
     */
    public static function register($key, $object, $readOnly = false)
    {
        if (is_object($object) === false)
        {
            throw new Willow_Blackboard_Exception(
                'Cannot register a non object on the black board'
            );
        }

        return self::_write($key, $object, $readOnly);
    }

    /**
     * Register an object constructor callback to $key
     *
     * @param string $key
     * @param mixed $callback A valid callback that returns an object
     * @param boolean $readOnly Do not allow re-registration of this key?
     * @throws Willow_Blackboard_Exception When $callback is not a valid callback
     * @return Willow_Blackboard Current Willow_Blackboard instance
     */
    public static function registerConstructor($key, $callback, $readOnly = false)
    {
        if (is_callable($callback) === false)
        {
            throw new Willow_Blackboard_Exception(
                'Cannot register a non callback as a constructor on the black board'
            );
        }

        return self::_write($key, $callback, $readOnly);
    }

    /**
     * Check if the object belonging to $key has been constructed
     *
     * @param string $key
     * @return boolean
     */
    public static function isConstructed($key)
    {
        if (self::isRegistered($key) === false ||
            isset(self::_instance()->_registered[$key]['constructor'])
           )
        {
            return false;
        }

        return true;
    }

    /**
     * Check if an object has been registered to $key
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
     * Write $item (an object or callback) to $key
     *
     * @param string $key
     * @param mixed $item An object or a valid callback
     * @param boolean $readOnly Do not allow re-registration of this key?
     * @throws Willow_Blackboard_Exception When $key may not be written to
     */
    private static function _write($key, $item, $readOnly)
    {
        if (self::_isWritable($key) === false)
        {
            throw new Willow_Blackboard_Exception(
                'Cannot re-register a read only key on the black board'
            );
        }

        self::_instance()->_registered[$key] = array(
            'instance' => $item,
            'readOnly' => ($readOnly ? true : false),
        );

        if (is_callable($item))
        {
            self::_instance()->_registered[$key]['constructor'] = $item;
        }

        return self::_instance();
    }

    /**
     * Check if $key may be written to
     *
     * @param string $key
     * @return boolean
     */
    private static function _isWritable($key)
    {
        if (self::isRegistered($key) === true)
        {
            if (self::_instance()->_registered[$key]['readOnly'] === true)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @var Willow_Blackboard
     */
    private static $_instance = null;

    /**
     * Retrieve current Willow_Blackboard instance
     *
     * @return Willow_Blackboard
     */
    private static function _instance()
    {
        if ((self::$_instance instanceof Willow_Blackboard) === false)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Retrieve registered object belonging to $key
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {
        if (self::isRegistered($key) === false)
        {
            return null;
        }

        if (isset(self::_instance()->_registered[$key]['constructor']))
        {
            self::_instance()->_registered[$key]['instance'] = 
                self::_instance()->_registered[$key]['constructor']($this);
            unset(self::_instance()->_registered[$key]['constructor']);
        }

        return self::_instance()->_registered[$key]['instance'];
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
