<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Password_SHA512 extends Willow_Cryptography_Password_Abstract
{

    private $_key = '$@pP$3Rv3r$P@s$';

    protected function _encrypt($plaintext, $salt)
    {
        return hash_hmac('sha512', $salt . $plaintext, $this->_key, false) . '$' . $salt . '$';
    }

    protected function _getSalt($encrypted = false)
    {
        if ($encrypted === false)
        {
            return hash('crc32', mt_rand());
        }

        if (preg_match('/\$(.+)\$$/', $encrypted, $matches))
        {
            return $matches[1];
        }

        return false;
    }

}
