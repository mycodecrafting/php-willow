<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Utils proxy
 */
class Willow_Utils implements Willow_Registerable_Interface
{

    /**
     * @var array alias to class mapping
     */
    protected $_classMap = array();

    /**
     * Prevent public construction of object
     */
    private function __construct()
    {
    }

    /**
     * Register a class under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $class)
    {
        self::_instance()->_classMap[$alias] = $class;
    }

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return string Util class name registered to alias
     * @throws Willow_Utils_Exception
     */
    public function getRegistered($alias)
    {
        /**
         * Alias is registered
         */
        if (array_key_exists($alias, self::_instance()->_classMap))
        {
            return self::_instance()->_classMap[$alias];
        }

        /**
         * Alias has not been registered
         */
        throw new Willow_Utils_Exception(sprintf(
            'Use of unregistered Willow_Utils alias, "%s"', $alias
        ));
    }

    /**
     * @var array Reflection cache
     */
    protected $_reflection = array();

    /**
     * ...
     */
    public static function __callStatic($method, $args)
    {
        $class = self::_instance()->getRegistered($method);

        /**
         * Reflection is expensive; let's not do it more than we have to
         */
        if (array_key_exists($class, self::_instance()->_reflection) === false)
        {
            self::_instance()->_reflection[$class] = new ReflectionClass($class);
        }

        return self::_instance()->_reflection[$class]->newInstanceArgs($args);
    }

    /**
     * @var Instance of self
     */
    private static $_instance = null;

    /**
     * Get the singleton instance of self
     */
    private static function _instance()
    {
        if ((self::$_instance instanceof self) === false)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

}
