<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Template engine interface
 */
interface Willow_Template_Engine_Interface
{

    /**
     * Build the template and return output
     *
     * @return string
     */
    public function build();

    /**
     * Get a set template var
     *
     * @return mixed
     */
    public function getVar($name);

    /**
     * Get all template vars
     *
     * @return array
     */
    public function getVars();

    /**
     * Set a template var to given value
     *
     * @param string $name Template var name
     * @param mixed $value Template var value
     * @return void
     */
    public function setVar($name, $value = null);

    public function import(array $vars);

    /**
     * Get the template file
     *
     */
    public function getTemplate();

    /**
     * Set the template file
     *
     * @param mixed $file The template file
     */
	public function setTemplate($file);

    /**
     * Check the template exists
     *
     * @return boolean
     */
    public function templateExists();

    /**
     * Can the engine have layouts (i.e., do they make sense)?
     *
     * @return boolean
     */
    public function hasLayout();

}
