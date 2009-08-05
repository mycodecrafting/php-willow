<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Payment_AuthorizeNet_Gateway_Abstract
{

    /**
     * ...
     */
    protected $_requestUri = null;

    /**
     * ...
     */
    public function doAuthorizationOnly(Willow_Payment_AuthorizeNet_Request $request)
    {
        $request->setType('AUTH_ONLY');
        return $this->_process($request);
    }

    /**
     * ...
     */
    protected function _process(Willow_Payment_AuthorizeNet_Request $request)
    {
        $conn = curl_init($this->_requestUri);

        curl_setopt($conn, CURLOPT_HEADER, 0);
	    curl_setopt($conn, CURLOPT_POST, 1);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_POSTFIELDS, $this->_prepareParameters($request->getParameters()));

        $response = curl_exec($conn);

        curl_close($conn);

        $response = new Willow_Payment_AuthorizeNet_Response($response, $request->getDelimChar());
        return $response;
    }

    /**
     * ...
     */
    protected function _prepareParameters(array $parameters)
    {
        return http_build_query($parameters, '', '&');
    }

}
