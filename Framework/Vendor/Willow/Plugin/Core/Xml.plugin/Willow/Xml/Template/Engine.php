<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Xml_Template_Engine implements Willow_Template_Engine_Interface
{

    /**
     * ...
     */
    protected $_root = 'root';

    /**
     * ...
     */
    public function setRoot($root)
    {
        $this->_root = $root;
    }

    /**
     * @var array
     */
    protected $_vars = array();

    /**
     * Build the template and return output
     *
     * @return string
     */
    public function build()
    {
        return $this->toXml();
    }

    /**
     * Get a set template var
     *
     * @return mixed
     */
    public function getVar($name)
    {
        if (isset($this->_vars[$name]))
        {
            return $this->_vars[$name];
        }
    }

    /**
     * Get all template vars
     *
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Set a template var to given value
     *
     * @param string $name Template var name
     * @param mixed $value Template var value
     * @return void
     */
    public function setVar($name, $value = null)
    {
        $this->_vars[$name] = $value;
    }

    /**
     * ...
     */
    public function import(array $vars)
    {
        $this->_vars = array_merge($this->_vars, $vars);
    }

    /**
     * ...
     */
    public function getTemplate()
    {
    }

    /**
     * Set the template file
     *
     * @param mixed $file The template file
     */
	public function setTemplate($file)
	{
	}

    /**
     * Check the template exists
     *
     * @return boolean
     */
    public function templateExists()
    {
        return true;
    }

    /**
     * Can the engine have layouts (i.e., do they make sense)?
     *
     * @return boolean
     */
    public function hasLayout()
    {
        return false;
    }

    /**
     * ...
     */
    protected function toXml($vars = null, $xml = null)
    {
        if ($vars === null)
        {
            $vars = $this->getVars();
        }

        if ($xml === null)
        {
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><{$this->_root} />");
        }

        /**
         * Foreach var
         */
        foreach ($vars as $key => $value)
        {
            /**
             * assume root -> root should actually be at root
             */
            if (($key === $this->_root) && is_array($value))
            {
                $this->toXml($value, $xml);
            }

            /**
             * assume numeric keys mean multi-dimensional array
             */
            elseif (is_numeric($key) && is_array($value))
            {
                foreach ($value as $key => $innerValue)
                {
                    if (is_array($innerValue))
                    {
                        $node = $xml->addChild($key);
                        $this->toXml($innerValue, $node);
                    }
                    else
                    {
                        $innerValue = htmlentities($innerValue);
                        $xml->addChild($key, $innerValue);
                    }
                }
            }

            /**
             * ...
             */
            else
            {
                if (is_array($value))
                {
                    $node = $xml->addChild($key);
                    $this->toXml($value, $node);
                }
                else
                {
                    $value = htmlentities($value);
                    $xml->addChild($key, $value);
                }
            }
        }

        return $xml->asXML();
    }

}
