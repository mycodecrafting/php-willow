<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Cipher
{

    private $_key;

    public function setKey($key)
    {
        $this->_cipher()->setKey($key);
        return $this;
    }

    public function getKey()
    {
        return $this->_cipher()->getKey();
    }

    public function setIv($iv)
    {
        $this->_cipher()->setIv($iv);
        return $this;
    }

    public function getIv()
    {
        return $this->_cipher()->getIv();
    }

    public function resetIv()
    {
        $this->_cipher()->resetIv();
        return $this;
    }

    public function encrypt($data)
    {
        return $this->_cipher()->encrypt($data);
    }

    public function decrypt($crypttext)
    {
        return $this->_cipher()->decrypt($crypttext);
    }

    private static $_cipherMap = array();

    public static function register($cipherAlias, $class)
    {
        self::$_cipherMap[$cipherAlias] = $class;
    }

    private static $_cipherAlias;

    public static function useCipher($alias)
    {
        self::$_cipherAlias = $alias;
    }

    protected $_cipher;

    protected function _cipher()
    {
        if (($this->_cipher instanceof Willow_Cryptography_Cipher_Abstract) === false)
        {
            $this->_cipher = self::_getCipher();
        }

        return $this->_cipher;
    }

    private static function _getCipher()
    {
        if (isset(self::$_cipherMap[self::$_cipherAlias]) === false)
        {
            throw new Willow_Cryptography_Exception(
                'Use of unregistered cipher alias "' . self::$_cipherAlias . '"'
            );
        }

        $cipher = new self::$_cipherMap[self::$_cipherAlias];

        if (($cipher instanceof Willow_Cryptography_Cipher_Abstract) === false)
        {
            throw new Willow_Cryptography_Exception(
                'Registered cipher ' . self::$_cipherMap[self::$_cipherAlias] .
                ' must inherit from Willow_Cryptography_Cipher_Abstract'
            );
        }

        return $cipher;
    }

    public function __get($property)
    {
        self::useCipher($property);
        return $this;
    }

}
