<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_View_Abstract implements Willow_View_Interface
{

    /**
     * @var Willow_Request
     */
    protected $_request;

    /**
     * @var
     */
    protected $_template;

    /**
     * ...
     */
    protected $_plexus;

    /**
     * Constructor
     */
    public function __construct(Willow_Request_Interface $request)
    {
        $this->_request = $request;
        $this->_template = new Willow_Template($request);

        /**
         * @todo
         */
        $this->_plexus = new Willow_Plexus();
    }

    /**
     * Get request object
     *
     * @return Willow_Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Get template object
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * ...
     */
    public function getPlexus()
    {
        return $this->_plexus;
    }

    /**
     * Render the view
     */
    public function render()
    {
        $this->preGenerate();

        $this->getPlexus()->doWillowViewGenerate();

        $this->getPlexus()->doGenerate($this);

        if ($this->isTemplateDisabled() === false)
        {
            $this->getTemplate()->build();
        }

        flush();
    }

    protected $_templateDisabled = false;

    /**
     * Disabled templating (maybe you are echoing directly from view class?)
     */
    public function setTemplateDisabled($disabled = true)
    {
        if ($disabled === true)
        {
            $this->_templateDisabled = true;
        }
        else
        {
            $this->_templateDisabled = false;
        }
    }

    /**
     * Is templating disabled?
     */
    public function isTemplateDisabled()
    {
        return $this->_templateDisabled;
    }

    // may be called by Willow_Actions_Abstract
    public function setValidationError($field, $message)
    {
    }

}
