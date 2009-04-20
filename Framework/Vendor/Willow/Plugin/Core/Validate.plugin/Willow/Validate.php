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
    public function add($field, Willow_Validate_Rule_Abstract $rule, $message = null)
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

        return $this;
    }

    /**
     * ...
     */
    protected $_errors = array();

    /**
     * ...
     */
    public function isValid()
    {
        $this->_errors = array();

        foreach ($this->_rules as $rule)
        {
            try
            {
                $rule['rule']->validate($this->_request->{$rule['field']});
            }
            catch (Willow_Validate_Rule_Exception $e)
            {
                $this->_errors[] = new Willow_Validate_Error($rule['field'], $e);
            }
        }

        return (count($this->_errors) > 0);
    }

    /**
     * ...
     */
    public function getErrors()
    {
        return $this->_errors;
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

    /**
     * ...
     */
    protected static $_reflectionCache = array();

    /**
     * ...
     */
    protected function _getValidator($alias)
    {
        $class = $this->getRegistered($alias);

        /**
         * Create reflection instance if not yet created in the cache
         */
        if (array_key_exists($class, self::$_reflectionCache) === false)
        {
            self::$_reflectionCache[$class] = new ReflectionClass($class);
        }

        return self::$_reflectionCache[$class];
    }

    /**
     * ...
     */
    protected $_currentField = null;

    /**
     * ...
     */
    public function __get($property)
    {
        $this->_currentField = $property;
        return $this;
    }

    /**
     * ...
     */
    public function __call($method, $args)
    {
        if ($this->_currentField === null)
        {
            throw new Willow_Validate_Exception(
                'Must first sepcify which field to validate on ' .
                'when using the fluid interface to Willow_Validate'
            );
        }

        if (count($args))
        {
            $validator = $this->_getValidator($method)->newInstanceArgs($args);
        }
        else
        {
            $validator = $this->_getValidator($method)->newInstance();
        }

        $field = $this->_currentField;
        $this->_currentField = null;

        $this->add($field, $validator);

        /**
         * Return $validator to allow access such as:
         *     $validate->fieldName->followsRule()->orError($withMessage)
         */
        return $validator;
    }

}
