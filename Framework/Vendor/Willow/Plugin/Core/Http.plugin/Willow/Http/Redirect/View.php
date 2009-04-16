<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Http_Redirect_View extends Willow_Http_View
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

    protected function _setHeaders()
    {
        $this->_setStatus($this->_redirect->getCode());\
		$this->_setHeader('Location', $this->_redirect->getUri());
    }

    public function generate()
    {
    }

    public function getStatusMessage($code = '')
    {
        return parent::getStatusMessage($this->_redirect->getCode());
    }

}
