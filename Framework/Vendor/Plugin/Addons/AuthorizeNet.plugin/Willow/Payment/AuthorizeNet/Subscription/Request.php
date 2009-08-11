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
    protected $_parameters = array(
        'subscriptionId'        => null,
        'name'                  => null,
        'intervalLength'        => '1',
        'intervalUnit'          => 'months',
        'startDate'             => '+30 Days',
        'totalOccurrences'      => '9999',
        'trialOccurrences'      => '0',
        'amount'                => '0',
        'trialAmount'           => '0.00',
        'cardNumber'            => null,
        'cardExpirationDate'    => null,
        'invoiceNumber'         => null,
        'emailAddress'          => null,
        'billToFirstName'       => null,
        'billToLastName'        => null,
        'billToZip'             => null,
    );

    /**
     * ...
     */
    protected $_root = null;

    /**
     * ...
     */
    public function __construct($login, $tranKey)
    {
        $this->_root = new Willow_Data_Node();
        $this->_root->setAttribute('xmlns', 'AnetApi/xml/v1/schema/AnetApiSchema.xsd');
        $this->_root->getMerchantAuthentication()->setName($login)->setTransactionKey($tranKey);
    }

    /**
     * ...
     */
    public function getFunction()
    {
        return $this->_root->getNodeName();
    }

    /**
     * ...
     */
    public function setFunction($functionName)
    {
        $this->_root->setNodeName($functionName);
        return $this;
    }

    /**
     * ...
     */
    public function getParameter($parameter)
    {
        if (array_key_exists($parameter, $this->_parameters) === true)
        {
            return $this->_parameters[$parameter];
        }
    }

    /**
     * ...
     */
    public function setParameter($parameter, $value)
    {
        $this->_parameters[$parameter] = $value;
        return $this;
    }

    /**
     * ...
     */
    public function asXml()
    {
        $this->_buildRequest();
        return $this->_root->asXml();
    }

    /**
     * ...
     */
    protected function _buildRequest()
    {
        /**
         * Set subscription ID
         */
        if ($this->getFuction() !== 'ARBCreateSubscriptionRequest')
        {
            $this->_root->setSubscriptionId($this->getParameter('subscriptionId'));
        }

        /**
         * That's all for cancelling
         */
        if ($this->getFunction() === 'ARBCancelSubscriptionRequest')
        {
            return;
        }

        /**
         * Set subscription name
         */
        if (($name = $this->getParameter('name')) !== null)
        {
            $this->_root->getSubscription()->setName($name);
        }

        /**
         * Set payment schedule interval
         */
        if ($this->getFuction() === 'ARBCreateSubscriptionRequest')
        {
            $this->_root->getSubscription()->getPaymentSchedule()->getInterval()
                ->setLength($this->getParameter('intervalLength'))
                ->setUnit($this->getParameter('intervalUnit'));

            /**
             * Set payment schedule start date, etc
             */
            $this->_root->getSubscription()->getPaymentSchedule()
                ->setStartDate(date('Y-m-d', strtotime($this->getParameter('startDate'))))
                ->setTotalOccurrences($this->getParameter('totalOccurrences'))
                ->setTrialOccurrences($this->getParameter('trialOccurrences'));

            /**
             * Set subscription amount
             */
            $this->_root->getSubscription()
                ->setAmount($this->getParameter('amount'))
                ->setTrialAmount($this->getParameter('trialAmount'));
        }

        /**
         * Set credit card info
         */
        if (($cardNumber = $this->getParameter('cardNumber')) !== null)
        {
            $this->_root->getSubscription()->getPayment()->getCreditCard()->setCardNumber($cardNumber);
        }

        if (($cardExpirationDate = $this->getParameter('cardExpirationDate')) !== null)
        {
            $this->_root->getSubscription()->getPayment()->getCreditCard()
                ->setExpirationDate(date('Y-m', strtotime($cardExpirationDate)));
        }

        /**
         * Set invoice number
         */
        if (($invoiceNumber = $this->getParameter('invoiceNumber')) !== null)
        {
            $this->_root->getSubscription()->getOrder()->setInvoiceNumber($invoiceNumber);
        }

        /**
         * Set customer email
         */
        if (($emailAddress = $this->getParameter('emailAddress')) !== null)
        {
            $this->_root->getSubscription()->getCustomer()->setEmail($emailAddress);
        }

        /**
         * Set billing info
         */
        if (($billToFirstName = $this->getParameter('billToFirstName')) !== null)
        {
            $this->_root->getSubscription()->getBillTo()
                ->setFirstName($this->getParameter('billToFirstName'))
                ->setLastName($this->getParameter('billToLastName'));
        }

        if (($billToZip = $this->getParameter('billToZip')) !== null)
        {
            $this->_root->getSubscription()->getBillTo()
                ->setZip($this->getParameter('billToZip'));
        }
    }

    /**
     * ...
     */
    protected function _normalize($string)
    {
        return strtolower(substr($string, 0, 1)) . substr($string, 1);
    }

    /**
     * ...
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) === 'set')
        {
            $parameter = $this->_normalize(substr($method, 3));
            return $this->setParameter($parameter, $args[0]);
        }

        if (substr($method, 0, 3) === 'get')
        {
            $parameter = $this->_normalize(substr($method, 3));
            return $this->getParameter($parameter);
        }
    }

}

/**

$request = new Willow_Payment_AuthorizeNet_Subscription_Request('loginId', 'transkeyxxx');

$request->getSubscription()->setName('INDIVIDUAL');
$request->getSubscription()->getPaymentSchedule()->getInterval()->setLength(1);
$request->getSubscription()->getPaymentSchedule()->getInterval()->setUnit('months');
$request->getSubscription()->getPaymentSchedule()->setStartDate(date('Y-m-d'));

 */