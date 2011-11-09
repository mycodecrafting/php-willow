<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * A simple registry interface
 */
interface Willow_Registry_Interface
{

    /**
     * Get the item registered for $key
     *
     * @param mixed $key Item key
     * @return mixed The registered item
     */
    public static function get($key);

    /**
     * Register $item under $key
     *
     * @param mixed $key Item key
     * @param mixed $item Item being registered
     * @param boolean $readOnly Register item as read-only?
     * @return void
     */
    public static function register($key, $item, $readOnly = false);

    /**
     * Check if an item is registered under $key
     *
     * @param mixed $key Item key
     * @return boolean
     */
    public static function isRegistered($key);

}
