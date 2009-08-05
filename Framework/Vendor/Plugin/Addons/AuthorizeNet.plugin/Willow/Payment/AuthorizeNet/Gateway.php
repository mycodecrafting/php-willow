<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Gateway
{

    const ENV_TEST       = 0;
    const ENV_PRODUCTION = 1;

    /**
     * ...
     */
    protected $_environment = 0;

    /**
     * ...
     */
    public function __construct($environment = 0)
    {
        $this->_environment = $environment;
    }

    /**
     * ...
     */
    protected $_client = null;

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
                    $this->_client = new Willow_Payment_AuthorizeNet_Gateway_Production();
                    break;

                case self::ENV_TEST:
                default:
                    $this->_client = new Willow_Payment_AuthorizeNet_Gateway_Test();
                    break;
            }
        }

        return $this->_client;
    }

    /**
     * ...
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_client(), $method), $args);
    }

}
