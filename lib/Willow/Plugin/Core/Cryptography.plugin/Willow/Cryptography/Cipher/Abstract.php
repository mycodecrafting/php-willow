<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Cryptography_Cipher_Abstract
{

    protected $_key;

    public function setKey($key)
    {
        $this->_key = $key;
    }

    public function getKey()
    {
        return $this->_key;
    }

    protected $_iv;

    public function setIv($iv)
    {
        $this->_iv = $iv;
    }

    public function getIv()
    {
        return $this->_iv;
    }

    public function resetIv()
    {
        $this->_iv = null;
    }

    abstract public function encrypt($data);
    abstract public function decrypt($crypttext);

}
