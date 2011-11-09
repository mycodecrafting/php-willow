<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Http_Application_Static extends Willow_Http_Application
{

    /**
     * ...
     */
    public function setActions(Willow_Actions_Interface $actions)
    {
        $this->_actions = $actions;
        return $this;
    }

    /**
     * Get the actions for the request
     *
     * @return Willow_Actions_Abstract
     */
    public function getActions()
    {
        if (($this->_actions instanceof Willow_Actions_Interface) === false)
        {
            throw new Willow_Application_Exception(
                'Must explicitly set Actions when using Willow_Http_Application_Static'
            );
        }

        return $this->_actions;
    }

    /**
     * ...
     */
    public function setView(Willow_View_Interface $view)
    {
        $this->_view = $view;
        return $this;
    }

    /**
     * Get the view for the request
     *
     * @return Willow_View_Abstract
     */
    public function getView()
    {
        if (($this->_view instanceof Willow_View_Interface) === false)
        {
            throw new Willow_Application_Exception(
                'Must explicitly set View when using Willow_Http_Application_Static'
            );
        }

        return $this->_view;
    }

}
