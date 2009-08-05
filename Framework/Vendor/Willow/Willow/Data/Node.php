<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Data_Node
{

    /**
     * ...
     */
    protected $_attributes = array();

    /**
     * ...
     */
    protected $_children = array();

    /**
     * ...
     */
    protected $_name = 'root';

    /**
     * ...
     */
    protected $_value = null;

    /**
     * ...
     */
    public function __construct($name = 'root')
    {
        $this->_name = $name;
    }

    /**
     * ...
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * ...
     */
    public function attributes()
    {
        return $this->_attributes;
    }

    /**
     * ...
     */
    public function getChild($name)
    {
        if ($this->hasChild($name) === false)
        {
            $this->createChild($name);
        }

        return $this->_children[$name];
    }

    /**
     * ...
     */
    public function setChild($name, $value)
    {
        $this->getChild($name)->setValue($value);
        return $this;
    }

    /**
     * ...
     */
    public function addChild(Willow_Data_Node $child)
    {
        return $this->createChild($child->getName(), $child);
    }

    /**
     * ...
     */
    public function createChild($name, Willow_Data_Node $child = null)
    {
        if ($child === null)
        {
            $child = new self($name);
        }

        if ($this->hasChild($name) === true)
        {
            if (!is_array($this->_children[$name]))
            {
                $this->_children[$name] = array($this->_children[$name]);
            }

            $this->_children[$name][] = $child;
        }
        else
        {
            $this->_children[$name] = $child;
        }

        return $child;
    }

    /**
     * ...
     */
    public function hasChild($name)
    {
        return array_key_exists($name, $this->_children);
    }

    /**
     * ...
     */
    public function hasChildren()
    {
        return (count($this->_children) > 0);
    }

    /**
     * ...
     */
    public function children()
    {
        return $this->_children;
    }

    /**
     * ...
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * ...
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * ...
     */
    public function __call($method, $properties)
    {
        /**
         * Map $this->setChildNode($value) to $this->childNode = $value
         */
        if (substr($method, 0, 3) === 'set')
        {
            $nodeName = strtolower(substr($method, 3, 1)) . substr($method, 4);
            return $this->setChild($nodeName, $properties[0]);
        }

        /**
         * Map $this->getChildNode() to $this->childNode
         */
        if (substr($method, 0, 3) === 'get')
        {
            $nodeName = strtolower(substr($method, 3, 1)) . substr($method, 4);
            return $this->$nodeName;
        }
    }

    /**
     *
     */
    public function __get($property)
    {
        return $this->getChild($property);
    }

    /**
     * ...
     */
    public function __set($property, $value)
    {
        if ($value instanceof self)
        {
            
        }
        else
        {
            return $this->setChild($property, $value);
        }
    }

    /**
     * ...
     */
    public function asXml()
    {
        $xml = sprintf("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<%1\$s></%1\$s>", $this->getName());
        $element = simplexml_load_string($xml);
        return $this->_asXml($element);
    }

    /**
     * ...
     */
    protected function _asXml(SimpleXMLElement $element)
    {
        /**
         * Attributes
         */
        foreach ($this->attributes() as $attribute => $value)
        {
            $element->setAttribute($attribute, $value);
        }

        /**
         * Children
         */
        foreach ($this->children() as $child)
        {
            $child->_asXml($element->addChild($child->getName(), $child->getValue()));
        }

        return $element->asXML();
    }

}
