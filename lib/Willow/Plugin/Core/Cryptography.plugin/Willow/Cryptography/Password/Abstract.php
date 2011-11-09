<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Cryptography_Password_Abstract
{

    abstract protected function _encrypt($plaintext, $salt);
    abstract protected function _getSalt($encrypted = false);

    public function encrypt($plaintext, $salt = false)
    {
        if ($salt === false)
        {
            $salt = $this->_getSalt();
        }

        return $this->_encrypt($plaintext, $salt);
    }

    public function validate($plaintext, $encrypted)
    {
        if (empty($plaintext) || empty($encrypted))
        {
            return false;
        }

        if (($salt = $this->_getSalt($encrypted)) === false)
        {
            return false;
        }

        if ($encrypted !== $this->encrypt($plaintext, $salt))
        {
            return false;
        }

        return true;
    }

}
