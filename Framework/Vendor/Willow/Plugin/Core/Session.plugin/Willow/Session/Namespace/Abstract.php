<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Session_Namespace_Abstract
{

    /**
     * ...
     */
    protected $_namespace;

    /**
     * ...
     */
    public function __construct($namespace)
    {
        $this->_namespace = $namespace;

        /**
         * Create in $_SESSION if it does not exist
         */
        if (!isset($_SESSION['__WF']['__NAMESPACES'][$namespace]))
        {
            $_SESSION['__WF']['__NAMESPACES'][$namespace] = array();
        }
    }

    /**
     * ...
     */
    public function getVar($name)
    {
        if (isset($this->$name))
        {
            return $_SESSION['__WF']['__NAMESPACES'][$this->_namespace][$name];
        }

        return null;
    }

    /**
     * ...
     */
    public function setVar($name, $value, $regenerate = false)
    {
        if ($regenerate === true)
        {
            Willow_Session::regenerateId();
        }

        $_SESSION['__WF']['__NAMESPACES'][$this->_namespace][$name] = $value;

        return $this;
    }

    /**
     * ...
     */
    public function __get($property)
    {
        return $this->getVar($property);
    }

    /**
     * ...
     */
    public function __set($property, $value)
    {
        return $this->setVar($property, $value);
    }

    /**
     * ...
     */
    public function __isset($key)
    {
        return array_key_exists($key, $_SESSION['__WF']['__NAMESPACES'][$this->_namespace]);
    }

    /**
     * ...
     */
    public function __unset($key)
    {
        unset($_SESSION['__WF']['__NAMESPACES'][$this->_namespace][$key]);
    }

}
