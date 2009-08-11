<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Payment_AuthorizeNet_Subscription_Gateway_Abstract
{

    /**
     * ...
     */
    protected $_requestUri = null;

    /**
     * ...
     */
    public function createSubscription(Willow_Payment_AuthorizeNet_Subscription_Request $request)
    {
        $request->setFunction('ARBCreateSubscriptionRequest');
        return $this->_process($request);
    }

    /**
     * ...
     */
    public function updateSubscription(Willow_Payment_AuthorizeNet_Subscription_Request $request)
    {
        $request->setFunction('ARBUpdateSubscriptionRequest');
        return $this->_process($request);
    }

    /**
     * ...
     */
    protected function _process(Willow_Payment_AuthorizeNet_Subscription_Request $request)
    {
        $conn = curl_init($this->_requestUri);

        curl_setopt($conn, CURLOPT_HEADER, 0);
	    curl_setopt($conn, CURLOPT_POST, 1);
        curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($conn, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($conn, CURLOPT_POSTFIELDS, $request->asXml());

        $response = curl_exec($conn);

        curl_close($conn);

        $response = new Willow_Payment_AuthorizeNet_Subscription_Response($response);

        return $response;
    }

}
