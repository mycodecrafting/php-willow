<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Session_Security_Abstract
{

    /**
     * ...
     */
    protected $_session = null;

    /**
     * ...
     */
    public function __construct()
    {
        $this->_session = Willow_Session::getNamespace('__WF_SECURITY');
    }

    /**
     * ...
     */
    abstract public function takeMeasures();

}
