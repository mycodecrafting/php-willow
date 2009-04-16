<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * A variable registry
 */
class Willow_Whiteboard implements Willow_Registry_Interface
{

    /**
     * @var array Registered variables
     */
    private $_registered = array();

    /**
     * Prevent public construction of object
     */
    private function __construct()
    {
    }

    /**
     * Get the item registered for $key
     *
     * @param mixed $key Item key
     * @return mixed The registered item
     */
    public static function get($key)
    {
        return self::_instance()->$key;
    }

    /**
     * Register $item under $key
     *
     * @param mixed $key Item key
     * @param mixed $item Item being registered
     * @param boolean $readOnly Register item as read-only?
     * @return void
     */
    public static function register($key, $item, $readOnly = false)
    {
        if (self::_isWritable($key) === false)
        {
            throw new Willow_Whiteboard_Exception(
                'Cannot re-register a read only key on the white board'
            );
        }

        self::_instance()->_registered[$key] = array(
            'value' => $item,
            'readOnly' => ($readOnly ? true : false),
        );
    }

    /**
     * Check if an item is registered under $key
     *
     * @param mixed $key Item key
     * @return boolean
     */
    public static function isRegistered($key)
    {
        return isset(self::_instance()->$key);
    }

    /**
     * Check if $key may be written to
     *
     * @param string $key
     * @return boolean
     */
    private static function _isWritable($key)
    {
        if (self::isRegistered($key) === true)
        {
            if (self::_instance()->_registered[$key]['readOnly'] === true)
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @var Willow_Whiteboard
     */
    private static $_instance = null;

    /**
     * Retrieve current Willow_Whiteboard instance
     *
     * @return Willow_Whiteboard
     */
    private static function _instance()
    {
        if ((self::$_instance instanceof Willow_Whiteboard) === false)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Retrieve registered item belonging to $key
     *
     * @param string $key
     * @return object
     */
    public function __get($key)
    {
        if (self::isRegistered($key) === false)
        {
            return null;
        }

        return self::_instance()->_registered[$key]['value'];
    }

    /**
     * Check if an object has been registered to $key
     *
     * @param string $key
     * @return boolean
     */
    public function __isset($key)
    {
        return array_key_exists($key, self::_instance()->_registered);
    }

}

/*
class VarRegistry {

	private static $store = array();

	public static function isValid($key) {
		return array_key_exists($key, self::$store);
	}

	public static function get($key) {
		if (self::isValid($key)) {
			return self::$store[$key];
		}
	}

	public static function set($key, $var) {
		self::$store[$key] = $var;
	}

	public static function &getByRef($key) {
		$return = null;
		if (self::isValid($key)) {
			$return =& self::$store[$key];
		}
		return $return;
	}

	public static function setByRef($key, &$var) {
		self::$store[$key] =& $var;
	}


	public static function printOut() {
		header('content-type: text/plain');
		var_export(self::$store);
		exit;
	}

}
*/
