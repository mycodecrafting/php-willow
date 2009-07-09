<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * XML view
 */
abstract class Willow_Xml_View extends Willow_Http_View
{

    /**
     * ...
     */
    protected $_templateExt = 'xml';

    /**
     * Set the default HTTP headers
     */
    protected function _setDefaultHeaders()
    {
        parent::_setDefaultHeaders();
        $this->_setContentType('text/xml');
    }

    public function preGenerate()
    {
        $this->setLayout(false);
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    }

}
