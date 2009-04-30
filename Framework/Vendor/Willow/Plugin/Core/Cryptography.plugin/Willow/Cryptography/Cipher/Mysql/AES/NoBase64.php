<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cryptography_Cipher_Mysql_AES_NoBase64 extends Willow_Cryptography_Cipher_Mysql_AES
{

    public function encrypt($data)
    {
        return base64_decode(parent::encrypt($data));
    }

    public function decrypt($crypttext)
    {
        return parent::decrypt(base64_encode($crypttext));
    }

}
