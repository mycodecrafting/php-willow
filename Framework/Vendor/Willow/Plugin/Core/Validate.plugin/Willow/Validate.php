<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate implements Willow_Registerable_Interface,
                                 Willow_Registerable_Transient_Interface
{

    /**
     * @var Willow_Request_Abstract
     */
    protected $_request = null;

    public function __construct(Willow_Request_Abstract $request)
    {
        $this->_request = $request;
    }

    /**
     * @var array validation rules
     */
    protected $_rules = array();

    /**
     * Add a validation rule to the current set
     */
    public function add($field, Willow_Validate_Rule_Asbtract $rule, $message = null)
    {
        $this->_rules[] = array(
            'field' => $field,
            'rule' => $rule,
        );

        /**
         * Set error message if passed
         */
        if ($message !== null)
        {
            $rule->orError($message);
        }
    }

    /**
     * ...
     */
    protected static $_classMap = array();

    /**
     * Register a class under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $class)
    {
        self::$_classMap[$alias] = $class;
    }

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return object
     * @throws Exception
     */
    public function getRegistered($alias)
    {
        /**
         * Transient alias is registered
         */
        if (array_key_exists($alias, $this->_transientMap) === true)
        {
            return $this->_transientMap[$alias];
        }

        /**
         * Alias is registered
         */
        elseif (array_key_exists($alias, self::$_classMap))
        {
            return self::$_classMap[$alias];
        }

        throw new Willow_Validate_Exception(sprintf(
            'Use of unregistered Willow_Validate alias, "%s"', $alias
        ));
    }

    /**
     * ...
     */
    protected $_transientMap = array();

    /**
     * Register a class under an alias for this instance
     *
     * @param string $alias
     * @param mixed $class
     * @return self
     */
    public function registerTransient($alias, $class)
    {
        $this->_transientMap[$alias] = $class;
    }

}
