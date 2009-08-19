<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request implements Willow_Request_Interface, Willow_Registerable_Interface, Willow_Registerable_Transient_Interface
{

    const METHOD_GET  = 'Get';
    const METHOD_POST = 'Post';

    protected $_path = '';

    protected $_module;
    protected $_section;
    protected $_action;

    protected $_method;
    protected $_protocol;


    /**
     * Constructor
     */
    public function __construct()
    {
        /**
         * Set path from PATH_INFO if not on root index
         */
		if (isset($this->server()->path_info) && ($this->server()->path_info !== '/'))
		{
            $this->_path = trim($this->server()->sanitized()->path_info, '/');
		}

        /**
         * Set request method
         */
        switch ($this->server()->request_method)
        {
            case 'GET':
            case 'POST':
            case 'DELETE':
            case 'PUT':
                $this->_method = $this->_normalize($this->server()->request_method);
                break;

            default:
                break;
        }

        /**
         * Set the request protocol (specified as ".protocol" on last URI segment)
         */
        $segments = explode('/', $this->_path);
        $segment = array_pop($segments);

        /**
         * default protocol
         */
        $protocol = self::getDefaultProtocol();

        /**
         * segment.protocol
         */
        if (($pos = strrpos($segment, '.')) !== false)
        {
            $protocol = substr($segment, $pos + 1);
            $segment = substr($segment, 0, $pos);

            /**
             * re-attach segment without protocol
             */
            $segments[] = $segment;
            $this->_path = implode('/', $segments);
        }

        $this->setProtocol($protocol);
    }

    /**
     * ...
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * Get the current module
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * ...
     */
    public function setModule($module)
    {
        $this->_module = $this->_normalize($module);
        return $this;
    }

    /**
     * Get the current module's section
     */
    public function getSection()
    {
        return $this->_section;
    }

    /**
     * ...
     */
    public function setSection($section)
    {
        $this->_section = $this->_normalize($section);
        return $this;
    }

    /**
     * ...
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * ...
     */
    public function setAction($action)
    {
        $this->_action = $this->_normalize($action);
        return $this;
    }

    /**
     * ...
     */
    public function getProtocol()
    {
        return $this->_protocol;
    }

    /**
     * ...
     */
    public function setProtocol($protocol)
    {
        $this->_protocol = $this->_normalize(
            self::getRegisteredProtocol($protocol)
        );
        return $this;
    }

    /**
     * ...
     */
    protected $_defaults = array(
        'module' => 'Default',
        'section' => 'Index',
        'action' => 'Index',
    );

    public function getDefaultModule()
    {
        return $this->_defaults['module'];
    }

    public function setDefaultModule($module)
    {
        $this->_defaults['module'] = $this->_normalize($module);
        return $this;
    }

    public function getDefaultSection()
    {
        return $this->_defaults['section'];
    }

    public function setDefaultSection($section)
    {
        $this->_defaults['section'] = $this->_normalize($section);
        return $this;
    }

    public function getDefaultAction()
    {
        return $this->_defaults['action'];
    }

    public function setDefaultAction($action)
    {
        $this->_defaults['action'] = $this->_normalize($action);
        return $this;
    }

    /**
     * Get a segment from the URI path
     *
     * @param integer $index Segment index to get
     * @return string
     */
    public function segment($index)
    {
        $index = intval($index) - 1;

        $segments = explode('/', $this->_path);

        if (isset($segments[$index]) && ($segments[$index] !== ''))
        {
            return $segments[$index];
        }

        return false;
    }

    /**
     * ...
     */
    public function segments()
    {
        return explode('/', $this->_path);
    }

    /**
     * Normalize strings-with-dashes_or_underscores to StringsWithCamelCase
     */
    protected function _normalize($string)
    {
        return Willow::utils()->string($string)->toCamelCase();
    }

    /**
     * @var array Allowed protocols
     */
    protected static $_registeredProtocols = array();

    /**
     * Register protocols allowed for this request
     */
    public static function registerProtocols()
    {
        foreach (func_get_args() as $protocol)
        {
            self::$_registeredProtocols[] = $protocol;
        }
    }

    /**
     * Check if a protocol is valid (i.e, has been registered)
     */
    public static function isValidProtocol($protocol)
    {
        return in_array($protocol, self::$_registeredProtocols);
    }

    /**
     * @var array Protocol alias map (alias => protocol)
     */
    protected static $_protocolAliasMap = array();

    /**
     * Register a protocol alias
     */
    public static function registerProtocolAlias($alias, $protocol)
    {
        if (self::isValidProtocol($protocol) === false)
        {
            throw new Willow_Request_Exception(sprintf(
                'Trying to register alias "%s" to invalid protocol "%s"',
                $alias,
                $protocol
            ));
        }

        self::$_protocolAliasMap[$alias] = $protocol;
    }

    /**
     * @var string Default protocol
     */
    protected static $_defaultProtocol = null;

    /**
     * Regsiter a default protocol
     */
    public static function registerDefaultProtocol($protocol)
    {
        if (self::isValidProtocol($protocol) === false)
        {
            throw new Willow_Request_Exception(sprintf(
                'Trying to register default protocol as invalid protocol "%s"',
                $protocol
            ));
        }

        self::$_defaultProtocol = $protocol;
    }

    /**
     * Get registered default protocol
     */
    public static function getDefaultProtocol()
    {
        return self::$_defaultProtocol;
    }

    /**
     * Get protocol registered to $alias
     */
    public static function getRegisteredProtocol($alias)
    {
        if (array_key_exists($alias, self::$_protocolAliasMap) === true)
        {
            $registered = self::$_protocolAliasMap[$alias];
        }
        else
        {
            $registered = $alias;
        }

        if (self::isValidProtocol($registered) === false)
        {
            throw new Willow_Request_Exception(sprintf(
                'Use of invalid protocol, ".%s"', $registered
            ));
        }

        return $registered;
    }




    protected static $_classMap = array();

    public static function register($alias, $class)
    {
        self::$_classMap[$alias] = $class;
    }

    protected $_transientMap = array();

    public function registerTransient($alias, $class)
    {
        $this->_transientMap[$alias] = $class;
    }

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
                $this->registerTransient($alias, new $class());
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
                self::register($alias, new $class());
            }

            $registered = self::$_classMap[$alias];
        }

        /**
         * Alias has not been registered
         */
        else
        {
            throw new Willow_Request_Exception(sprintf(
                'Use of unregistered Willow_Request alias, "%s"', $alias
            ));
        }

        /**
         * Force $registered type of Willow_Request_Abstract
         */
        if (($registered instanceof Willow_Request_Abstract) === false)
        {
            throw new Willow_Request_Exception(sprintf(
                'Class "%s" registered as alias to Willow_Request::%s ' .
                'must be an instance of Willow_Request_Abstract',
                get_class($registered),
                $alias
            ));
        }

        return $registered;
    }


    public function __call($method, $args)
    {
        return $this->getRegistered($method);
    }

}




/*



---

$request->segment(2);

---

Willow_Request::register('get', 'Willow_Request_Get');

$request->get()->param;

*/