<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * JSON view
 */
abstract class Willow_Json_View extends Willow_Http_View
{

    /**
     * Render the view
     */
    public function render()
    {
        $this->_setDefaultHeaders();
        $this->_setHeaders();

        $this->setLayout(false);

        Willow_View_Abstract::render();
    }

    /**
     * Set the default HTTP headers
     */
    protected function _setDefaultHeaders()
    {
        parent::_setDefaultHeaders();
        $this->_setContentType('application/json');
    }

    public function preGenerate()
    {
    }

}
