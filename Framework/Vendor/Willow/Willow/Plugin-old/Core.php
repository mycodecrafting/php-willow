<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Core plugin abstract class
 */
abstract class Willow_Plugin_Core extends Willow_Plugin
{

    /**
     * @var string Plugin title
     */
    public $title = '';

    /**
     * @var string Plugin description
     */
    public $descr = '';

    /**
     * @var Willow_Yaml_Node Plugin settings
     */
    protected $_settings = null;

    /**
     * @var Willow_Yaml_Node Plugin dictionary
     */
    protected $_dictionary = null;

    /**
     * Constructor
     *
     * @param Willow_Yaml_Node $settings Plugin settings object
     */
    public function __construct(Willow_Yaml_Node $settings = null)
    {
        $this->_settings = $settings;
    }

    /**
     * Set the dictionary object and set the plugin's title & description
     *
     * @param Willow_Yaml_Node $dictionary Plugin dictionary object
     */
    public function setDictionary(Willow_Yaml_Node $dictionary)
    {
        $this->_dictionary = $dictionary;
        $this->title = $this->_dictionary->title;
        $this->descr = $this->_dictionary->description;
    }

}
