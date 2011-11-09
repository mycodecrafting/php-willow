<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Cipher_AES128 extends Willow_Cryptography_Cipher_Mcrypt
{

    public function getAlgorithm()
    {
        return MCRYPT_RIJNDAEL_128;
    }

    public function getMode()
    {
        return MCRYPT_MODE_CFB;
    }

}
