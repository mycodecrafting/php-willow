<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized implements Willow_Registerable_Interface, Willow_Registerable_Transient_Interface
{

    /**
     * @var Willow_Request_Abstract
     */
    protected $_request;

    /**
     * Constructor
     *
     * @param Willow_Request_Abstract $request
     */
    public function __construct(Willow_Request_Abstract $request)
    {
        $this->_request = $request;
    }

    /**
     * @var array alias to class mapping
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
     * @var array transient alias to class mapping
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
        return $this;
    }

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return Willow_Request_Sanitized_Abstract
     * @throws Willow_Request_Sanitized_Exception
     */
    public function getRegistered($alias)
    {
        /**
         * Transient alias is registered
         */
        if (array_key_exists($alias, $this->_transientMap) === true)
        {
            if (is_string($this->_transientMap[$alias]))
            {
                $class = $this->_transientMap[$alias];
                $this->registerTransient($alias, new $class($this->_request));
            }

            $registered = $this->_transientMap[$alias];
        }

        /**
         * Alias is registered
         */
        elseif (array_key_exists($alias, self::$_classMap))
        {
            if (is_string(self::$_classMap[$alias]))
            {
                $class = self::$_classMap[$alias];
                self::register($alias, new $class($this->_request));
            }

            $registered = self::$_classMap[$alias];
        }

        /**
         * Alias has not been registered
         */
        else
        {
            throw new Willow_Request_Sanitized_Exception(sprintf(
                'Use of unregistered Willow_Request_Sanitized alias, "%s"', $alias
            ));
        }

        /**
         * Force $registered type of Willow_Request_Abstract
         */
        if (($registered instanceof Willow_Request_Sanitized_Abstract) === false)
        {
            throw new Willow_Request_Sanitized_Exception(sprintf(
                'Class "%s" registered as alias to Willow_Request_Sanitized::%s ' .
                'must be an instance of Willow_Request_Sanitized_Abstract',
                get_class($registered),
                $alias
            ));
        }

        return $registered;
    }

    /**
     * @var Willow_Request_Sanitized_Abstract
     */ 
    protected $_sanitizer;

    /**
     * Setup the sanitizer
     */
    public function __call($method, $args)
    {
        return $this->getRegistered($method);
    }

    /**
     * No sanitizer called; use default
     */
    public function __get($property)
    {
        try
        {
            $sanitizer = $this->getRegistered('default');
        }
        catch (Willow_Request_Sanitized_Exception $e)
        {
            $sanitizer = new Willow_Request_Sanitized_Default($this->_request);
        }

        return $sanitizer->$property;
    }

}

/*

    Willow_Request_Sanitized::register('asInt', 'Willow_Request_Sanitized_AsInt');

    $request->post()->sanitized()->paramName;
    $request->post()->sanitized()->asInt()->paramName;
    $request->post()->sanitized()->asInt()->setBase(8)->paramName;

*/
