<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
interface Willow_Registerable_Interface
{

    /**
     * Register a class under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $class);

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return object
     * @throws Exception
     */
    public function getRegistered($alias);

}
