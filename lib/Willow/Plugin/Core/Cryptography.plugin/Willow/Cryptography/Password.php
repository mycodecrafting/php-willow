<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Cryptography_Password
{

    public function __construct($hash = null)
    {
        if ($hash !== null)
        {
            if (($hash instanceof Willow_Cryptography_Password_Abstract) === false)
            {
                $class = 'Willow_Cryptography_Password_' . $hash;
                $hash = new $class;
            }

            $this->_hash = $hash;
        }
    }

    public function encrypt($plaintext)
    {
        return $this->_hash()->encrypt($plaintext);
    }

    public function validate($plaintext, $encrypted)
    {
        return $this->_hash()->validate($plaintext, $encrypted);
    }

	public function generate($length)
	{
		$vowels = array(
			'a', 'e', 'i', 'o', 'u', 'y'
		);

		$cons = array(
			'b', 'c', 'd', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'p', 
			'r', 's', 't', 'u', 'v', 'w', 'tr', 'cr', 'br', 'fr', 'th', 'dr', 'ch', 
			'ph', 'wr', 'st', 'sp', 'sw', 'pr', 'sl', 'cl',
		);

		$num_vowels = count($vowels);
		$num_cons = count($cons);

		$password = '';

		for ($i=0; $i<$length; ++$i)
		{
			$password .= $cons[rand(0, $num_cons - 1)] . $vowels[rand(0, $num_vowels - 1)];
		}

		$password = strtolower(substr($password, 0, $length - 1)) . rand(0, 9);

		return $password;
	}

    private static $_hashClass;

    public static function register($class)
    {
        self::$_hashClass = $class;
    }

    protected $_hash;

    protected function _hash()
    {
        if (($this->_hash instanceof Willow_Cryptography_Password_Abstract) === false)
        {
            $this->_hash = self::_getHash();
        }

        return $this->_hash;
    }

    private static function _getHash()
    {
        if (!isset(self::$_hashClass))
        {
            throw new Willow_Cryptography_Exception(
                'No password hashing class registered'
            );
        }

        $hash = new self::$_hashClass;

        if (($hash instanceof Willow_Cryptography_Password_Abstract) === false)
        {
            throw new Willow_Cryptography_Exception(
                'Registered password hashing class ' . self::$_hashClass .
                ' must inherit from Willow_Cryptography_Password_Abstract'
            );
        }

        return $hash;
    }

}
