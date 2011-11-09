<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Cipher_AES256 extends Willow_Cryptography_Cipher_Mcrypt
{

    public function getAlgorithm()
    {
        return MCRYPT_RIJNDAEL_256;
    }

    public function getMode()
    {
        return MCRYPT_MODE_CFB;
    }

}
