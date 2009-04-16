<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Request_Abstract
{

    /**
     * @var array Request properties
     */
    protected $_properties = array();

    /**
     * @var Willow_Request_Sanitized
     */
    protected $_sanitized;

    /**
     * Import properties into the request
     *
     * @param array $properties Key => value pairs of properties to import
     * @return void
     */
    public function import($properties)
    {
        $this->_properties = array_merge($this->_properties, $properties);
    }

    /**
     * Get the numerical count of the request properties
     *
     * @return integer Count of request properties
     */
    public function count()
    {
        return count($this->_properties);
    }

    /**
     * Get a sanitized version of request
     */
    public function sanitized()
    {
        if (($this->_sanitized instanceof Willow_Request_Sanitized) === false)
        {
            $this->_sanitized = new Willow_Request_Sanitized($this);
        }

        return $this->_sanitized;
    }

    public function __get($property)
    {
        if (isset($this->_properties[$property]))
        {
            return $this->_properties[$property];
        }
    }

    public function __set($property, $value)
    {
        $this->_properties[$property] = $value;
    }

    public function __isset($property)
    {
        if (isset($this->_properties[$property]))
        {
            return true;
        }

        return false;
    }

    public function __unset($property)
    {
        if (isset($this->_properties[$property]))
        {
            unset($this->_properties[$property]);
        }
    }

}
