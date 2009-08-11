<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Subscription_Response
{

    /**
     * ...
     */
    protected $_response;

    public function __construct($responseXml)
    {
        $responseXml = str_replace('xmlns="AnetApi', 'xmlns="https://api.authorize.net', $responseXml);
        $this->_response = simplexml_load_string($responseXml);
    }

    /**
     * ...
     */
    public function getSubscriptionId()
    {
        return strval($this->_response->subscriptionId);
    }

    /**
     * ...
     */
    public function getMessageText()
    {
        return strval($this->_response->messages->message->text);
    }

    /**
     * ...
     */
    public function isSuccessful()
    {
        return (strval($this->_response->messages->resultCode) === 'Ok');
    }

}
