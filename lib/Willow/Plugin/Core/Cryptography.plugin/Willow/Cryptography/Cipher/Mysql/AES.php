<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compatible with MySQL AES_* functions (provided strlen($key) < 16)
 */
class Willow_Cryptography_Cipher_Mysql_AES extends Willow_Cryptography_Cipher_Abstract
{

    protected $_algo = MCRYPT_RIJNDAEL_128;
    protected $_mode = MCRYPT_MODE_ECB;

    /**
     * Force strlen($key) to be < 16
     */
    public function setKey($key)
    {
        $this->_key = substr($key, 0, 16);
    }

    public function encrypt($data)
    {
        $length = strlen($data);
        $data = str_pad($data, (16 * (floor($length / 16) + (($length % 16) == 0 ? 2 : 1))), chr(16 - ($length % 16)));

        srand();

        return base64_encode(mcrypt_encrypt(
            $this->_algo,
            $this->getKey(),
            $data,
            $this->_mode,
            mcrypt_create_iv(mcrypt_get_iv_size($this->_algo, $this->_mode), MCRYPT_RAND)
        ));
    }

    public function decrypt($crypttext)
    {
        $data = mcrypt_decrypt(
            $this->_algo,
            $this->getKey(),
            base64_decode($crypttext),
            $this->_mode,
            mcrypt_create_iv(mcrypt_get_iv_size($this->_algo, $this->_mode), MCRYPT_RAND)
        );

        /**
         * Since the aes_encrypt right-pads N * blocksize with any chr( 0 ) to chr( 16 ) 
         * (random based on the input string length) we first decrypt the text, then RTrim 
         * chr(0 .. 16) depending on its trailing ord() value.
         */
        $eolOrd = ord(substr($data, -1));

        if (($eolOrd >= 0) && ($eolOrd <= 16))
        {
            $data = rtrim($data, chr($eolOrd));
        }

        return $data;
    }

}
