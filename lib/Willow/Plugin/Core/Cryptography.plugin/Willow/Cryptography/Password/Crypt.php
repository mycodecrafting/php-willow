<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Cryptography_Password_Crypt extends Willow_Cryptography_Password_Abstract
{

    protected function _encrypt($plaintext, $salt)
    {
        if ($salt === false)
        {
            return crypt($plaintext);
        }

        return crypt($plaintext, $salt);
    }

    protected function _getSalt($encrypted = false)
    {
        /**
         * From PHP manual:
         *      You should pass the entire results of crypt() as the salt for comparing a
         *      password, to avoid problems when different hashing algorithms are used. (As
         *      it says above, standard DES-based password hashing uses a 2-character salt,
         *      but MD5-based hashing uses 12.)
         */
        return $encrypted;
    }

}
