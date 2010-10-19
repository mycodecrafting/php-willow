<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Data_Object implements ArrayAccess, IteratorAggregate
{

    /**
     * ...
     */
    protected $_properties = array();

    /**
     * ...
     */
    public function __construct(array $properties = array())
    {
        if (count($properties) > 0)
        {
            $this->import($properties);
        }
    }

    /**
     * ...
     */
    public function import(array $properties)
    {
        foreach ($properties as $property => $value)
        {
            $this->set($property, $value);
        }
    }

    /**
     * ...
     */
    public function get($property)
    {
        if (isset($this->$property) === true)
        {
            return $this->_properties[$property];
        }
    }

    /**
     * ...
     */
    public function set($property, $value)
    {
        $this->_properties[$property] = $value;
    }

    /**
     * ...
     */
    public function isValid($property)
    {
        return array_key_exists($property, $this->_properties);
    }

    /**
     * ...
     */
    public function remove($property)
    {
        unset($this->_properties[$property]);
    }

    /**
     * For IteratorAggregate
     */
    public function getIterator()
    {
        return new ArrayIterator($this->_properties);
    }

    /**
     * For ArrayAccess
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * For ArrayAccess
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * For ArrayAccess
     */
    public function offsetExists($offset)
    {
        return $this->isValid($offset);
    }

    /**
     * For ArrayAccess
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * ...
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * ...
     */
    public function __set($property, $value)
    {
        return $this->set($property, $value);
    }

    /**
     * ...
     */
    public function __isset($property)
    {
        return $this->isValid($property);
    }

    /**
     * ...
     */
    public function __unset($property)
    {
        return $this->remove($property);
    }

}
