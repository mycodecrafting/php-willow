<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * YAML Node
 */
class Willow_Yaml_Node implements ArrayAccess, IteratorAggregate
{

    /**
     * Name $___children to prevent collision with any children actually called "children"
     */
    protected $___children = array();

    public function __construct($yaml = null)
    {
        if ($yaml !== null)
        {
            $this->assimilateData($yaml);
        }
    }


    public function children()
    {
        return $this->___children;
    }


    public function addChildNode($name)
    {
        if (!isset($this->___children[$name]))
        {
            $this->___children[$name] = new self();
        }

        return $this->___children[$name];
    }


    public function addKeyValuePair($key, $value)
    {
        $this->___children[$key] = $value;
    }


    public function appendKeyValuePair($key, $value)
    {
        if ($this->___children[$key] instanceof self)
        {
            $this->___children[$key] = null;
        }

        if ($this->___children[$key] !== null)
        {
            $value = $this->___children[$key] . ' ' . $value;
        }

        $this->addKeyValuePair($key, $value);
    }


    public function addSeriesValue($key, $value)
    {
        if ((array_key_exists($key, $this->___children) === false) ||
            (is_array($this->___children[$key]) === false)
        )
        {
            $this->___children[$key] = array();
        }

        $this->___children[$key][] = $value;
    }


    public function assimilate($yaml)
    {
        if ($yaml instanceof self)
        {
            return $this->assimilateYamlNode($yaml);
        }

        $parser = new Willow_Yaml_Parser($this);
        $parser->assimilateData($yaml);        
    }


    /**
     * @deprecated
     */
    public function assimilateData($yaml)
    {
        return $this->assimilate($yaml);
    }


    protected function assimilateYamlNode(Willow_Yaml_Node $yaml)
    {
        foreach ($yaml->children() as $key => $value)
        {
            if ($value instanceof self)
            {
                $this->addChildNode($key)->assimilateYamlNode($value);
            }
            else
            {
                $this->addKeyValuePair($key, $value);
            }
        }
    }


    public function toArray()
    {
        $return = array();
        foreach ($this->___children as $key => $value)
        {
            if (($value instanceof Willow_Yaml_Node) !== false)
            {
                $return[$key] = $value->toArray();
            }
            else
            {
                $return[$key] = $value;
            }
        }
        return $return;
    }


    /**
     * for IteratorAggregate
     */
    public function getIterator()
    {
        return new ArrayIterator($this->___children);
    }


    /**
     * for ArrayAccess
     * {{{
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value) {
        $this->___children[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
    /**
     * }}}
     */


    /**
     * Magic methods
     * {{{
     */
    public function __get($property)
    {
        if (!isset($this->$property))
        {
            return;
        }

        return $this->___children[$property];
    }

    public function __isset($property)
    {
        if (array_key_exists($property, $this->___children))
        {
            return true;
        }

        return false;
    }

    public function __unset($offset)
    {
        unset($this->___children[$offset]);
    }
    /**
     * }}}
     */

}
