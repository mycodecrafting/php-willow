<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Subscription_Gateway extends Willow_Payment_AuthorizeNet_Gateway
{

    const ENV_TEST       = 0;
    const ENV_PRODUCTION = 1;

    /**
     * ...
     */
    protected function _client()
    {
        if (($this->_client instanceof Willow_Payment_AuthorizeNet_Gateway_Abstract) === false)
        {
            switch ($this->_environment)
            {
                case self::ENV_PRODUCTION:
                    $this->_client = new Willow_Payment_AuthorizeNet_Subscription_Gateway_Production();
                    break;

                case self::ENV_TEST:
                default:
                    $this->_client = new Willow_Payment_AuthorizeNet_Subscription_Gateway_Test();
                    break;
            }
        }

        return $this->_client;
    }

}
