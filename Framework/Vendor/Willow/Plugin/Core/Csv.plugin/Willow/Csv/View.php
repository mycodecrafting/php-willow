<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * CSV view
 */
abstract class Willow_Csv_View extends Willow_Http_View
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
        $this->_setContentType('text/plain');
    }

    public function preGenerate()
    {
    }

}
