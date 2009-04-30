<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Cryptography_Cipher_Mcrypt extends Willow_Cryptography_Cipher_Abstract
{

    protected $_mcrypt;

    public function __construct()
    {
        $this->_mcrypt = mcrypt_module_open(
            $this->getAlgorithm(), '', $this->getMode(), ''
        );
    }

    public function __destruct()
    {
        mcrypt_module_close($this->_mcrypt);
    }

    public function encrypt($data)
    {
        mcrypt_generic_init($this->_mcrypt, $this->getKey(), $this->_getRawIv());
        $crypttext = base64_encode(mcrypt_generic($this->_mcrypt, serialize($data)));
        mcrypt_generic_deinit($this->_mcrypt);

        return $crypttext;
    }

    public function decrypt($crypttext)
    {
        mcrypt_generic_init($this->_mcrypt, $this->getKey(), $this->_getRawIv());
        $data = unserialize(mdecrypt_generic($this->_mcrypt, base64_decode($crypttext)));
        mcrypt_generic_deinit($this->_mcrypt);

        return $data;
    }

    protected function _getRawIv()
    {
        if ($this->_iv === null)
        {
    		$this->_iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->_mcrypt), MCRYPT_RAND);            
        }

        return $this->_iv;
    }

    public function getIv()
    {
        return base64_encode($this->_getRawIv());
    }

    public function setIv($iv)
    {
        $this->_iv = base64_decode($iv);
    }

    abstract public function getAlgorithm();
    abstract public function getMode();

}
