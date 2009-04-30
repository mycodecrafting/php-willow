<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Password_MD5 extends Willow_Cryptography_Password_Abstract
{

    protected function _encrypt($plaintext, $salt)
    {
        return md5($plaintext);
    }

    protected function _getSalt($encrypted = false)
    {
        return 'salt not used';
    }

}
