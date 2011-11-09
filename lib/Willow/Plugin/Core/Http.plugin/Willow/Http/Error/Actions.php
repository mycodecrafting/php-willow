<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Http_Error_Actions extends Willow_Http_Actions
{

    /**
     * @var Willow_Http_Error
     */
    protected $_error;

    /**
     * Sets the error
     *
     * @param Willow_Http_Error $error
     * @return void
     */
    public function setError(Willow_Http_Error $error)
    {
        $this->_error = $error;
    }

    public function doAction()
    {
    }

}
