<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Csv_Template_Engine implements Willow_Template_Engine_Interface
{

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
        if (($csv = $this->getVar('csv')) !== null)
        {
            return $csv;
        }
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
    public function setVars(array $vars)
    {
        $this->_vars = $vars;
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

}
