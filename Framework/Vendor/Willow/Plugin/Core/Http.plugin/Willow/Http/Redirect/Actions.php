<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Http_Redirect_Actions extends Willow_Http_Actions
{

    /**
     * @var Willow_Http_Redirect
     */
    protected $_redirect;

    /**
     * Sets the redirection
     *
     * @param Willow_Http_Redirect $redirect
     * @return void
     */
    public function setRedirect(Willow_Http_Redirect $redirect)
    {
        $this->_redirect = $redirect;
    }

    public function doAction()
    {
    }

}
