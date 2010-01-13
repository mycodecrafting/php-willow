<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Template_Adapter_Motif implements Willow_Template_Engine_Interface
{

    /**
     * @var Motif_Template
     */
    protected $_template;

    /**
     * Constructor
     */
    public function __construct()
    {
        $cache = true;

        if (Willow_Blackboard::get('config')->app->environment === 'development')
        {
            $cache = false;
        }

        $this->_template = new Motif_Template($cache);
    }

    /**
     * Build the template and return output
     *
     * @return string
     */
    public function build()
    {
        return $this->_template->build();
    }

    /**
     * Get a set template var
     *
     * @return mixed
     */
    public function getVar($name)
    {
        return $this->_template->getVar($name);
    }

    /**
     * Get all template vars
     *
     * @return array
     */
    public function getVars()
    {
        return $this->_template->getVars();
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
        $this->_template->setVar($name, $value);
    }

    /**
     * ...
     */
    public function setVars(array $vars)
    {
        $this->_template->setVars($vars);
    }

    /**
     * ...
     */
    public function import(array $vars)
    {
        $this->_template->setVars(array_merge($this->getVars(), $vars));
    }

    /**
     * Get the template file
     *
     */
    public function getTemplate()
    {
        return $this->_template->getTemplate();
    }

    /**
     * Set the template file
     *
     * @param mixed $file The template file
     * @return void
     */
	public function setTemplate($file)
	{
        $this->_template->setTemplate($file);
	}

    /**
     * Check the template exists
     *
     * @return boolean
     */
    public function templateExists()
    {
        return $this->_template->templateExists();
    }

    /**
     * Can the engine have layouts (i.e., do they make sense)?
     *
     * @return boolean
     */
    public function hasLayout()
    {
        return true;
    }

}
