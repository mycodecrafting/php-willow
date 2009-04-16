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
    protected static $_classMap = array();

    /**
     * Register a class under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $class)
    {
        self::$_classMap[$alias] = $class;
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
        if (array_key_exists($alias, self::$_classMap))
        {
            return self::$_classMap[$alias];
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

    public function __call($method, $args)
    {
        $class = self::getRegistered($method);

        /**
         * Reflection is expensive; let's not do it more than we have to
         */
        if (array_key_exists($class, $this->_reflection) === false)
        {
            $this->_reflection[$class] = new ReflectionClass($class);
        }

        return $this->_reflection[$class]->newInstanceArgs($args);
    }

}
