<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Subscription_Request
{

    /**
     * ...
     */
    protected $_nodes = array(
        'merchantAuthentication' => null,
    );

    /**
     * ...
     */
    public function getNode($nodeName)
    {
        if ($this->_nodes[$nodeName] === null)
        {
            $this->_nodes[$nodeName] = new Willow_Data_Node($nodeName);
        }

        return $this->_nodes[$nodeName];
    }

    /**
     * ...
     */
    public function __call($method, $properties)
    {
        if (substr($method, 0, 3) !== 'get')
        {
            throw ...
        }

        $nodeName = strtolower(substr($method, 3, 1)) . substr($method, 4);

        return $this->getNode($nodeName);
    }

}

/**

$request = new Willow_Payment_AuthorizeNet_Subscription_Request();
$request->getMerchantAuthentication()->setName('loginId');
$request->getMerchantAuthentication()->setTransactionKey('xxxxxx');
$request->getSubscription()->setName('INDIVIDUAL');
$request->getSubscription()->getPaymentSchedule()->getInterval()->setLength(1);
$request->getSubscription()->getPaymentSchedule()->getInterval()->setUnit('months');
$request->getSubscription()->getPaymentSchedule()->setStartDate(date('Y-m-d));

 */