<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
interface Willow_Session_Namespace_Handler_Interface
{

    /**
     * ...
     */
    public static function getNamespace($namespace);

    /**
     * Create a namespace within the session
     *
     * @param string $namespace
     * @return Willow_Session_Namespace
     * @throws Willow_Session_Exception When namespace has already been created
     */
    public static function createNamespace($namespace);

    /**
     * Check if a namespace has been created
     *
     * @return boolean
     */
    public static function hasNamespace($namespace);

}
