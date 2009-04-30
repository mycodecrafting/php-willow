<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Register Cipher aliases
 */
Willow_Cryptography_Cipher::register('aes128', 'Willow_Cryptography_Cipher_AES128');
Willow_Cryptography_Cipher::register('aes256', 'Willow_Cryptography_Cipher_AES256');
Willow_Cryptography_Cipher::register('mysql', 'Willow_Cryptography_Cipher_Mysql_AES');

/**
 * Register default password hashing handler
 */
Willow_Cryptography_Password::register('Willow_Cryptography_Password_SHA512');
