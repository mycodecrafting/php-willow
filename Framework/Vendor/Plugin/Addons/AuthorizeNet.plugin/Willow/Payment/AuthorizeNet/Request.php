<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Request
{

    /**
     * ...
     */
    protected $_parameters = array();

    /**
     * ...
     */
    public function __construct($login, $tranKey, $testRequest = false)
    {
        $this->setVersion('3.1')
            ->setMethod('CC')
            ->setDelimData(true)
            ->setDelimChar('|')
            ->setRelayResponse(false)
            ->setLogin($login)
            ->setTranKey($tranKey)
            ->setTestRequest($testRequest);
    }

    /**
     * ...
     */
    public function getParameters()
    {
        return $this->_parameters;
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
        if (is_bool($value))
        {
            switch ($value)
            {
                case true:
                    $value = 'TRUE';
                    break;

                case false:
                    $value = 'FALSE';
                    break;
            }
        }

        $this->_parameters[$parameter] = $value;

        return $this;
    }

    /**
     * ...
     */
    protected function _normalize($string)
    {
        return strtolower(preg_replace('/(?<=[a-z])(?=[A-Z])/', '_', $string));
    }

    /**
     * ...
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) === 'set')
        {
            $parameter = 'x_' . $this->_normalize(substr($method, 3));
            return $this->setParameter($parameter, $args[0]);
        }

        if (substr($method, 0, 3) === 'get')
        {
            $parameter = 'x_' . $this->_normalize(substr($method, 3));
            return $this->getParameter($parameter);
        }
    }

}
