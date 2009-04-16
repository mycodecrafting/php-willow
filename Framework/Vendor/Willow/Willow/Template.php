<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Templating
 */
class Willow_Template implements Willow_Registerable_Interface
{

    /**
     * @var Willow_Template_Engine_Interface Template engine
     */
    protected $_engine;

    /**
     * Constructor
     *
     * @param Willow_Request $request
     */
    public function __construct(Willow_Request $request)
    {
        $this->_engine = $this->getRegistered($request->getProtocol());
    }

    /**
     * Build & output the template
     */
    public function build()
    {
        /**
         * Check template exists
         */
        if ($this->_engine->templateExists() === false)
        {
            throw new Willow_Http_Error('Not Found');
        }

        /**
         * build main template
         */
        $output = $this->_engine->build();

        /**
         * build layout
         */
        if ($this->_engine->hasLayout() === true && $this->getLayout() !== false)
        {
            $output = $this->_buildLayout($output);
        }

        /**
         * output
         */
        if ($output !== false)
        {
            echo $output;
        }
    }

    /**
     * ...
     */
    protected $_layout = false;

    /**
     * ...
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * ...
     */
    public function setLayout($dataPath)
    {
        if ($dataPath !== false)
        {
            if (($layout = Willow_Loader::getRealPath($dataPath, $overridable = true, $ext = 'html')) === false)
            {
                throw new Willow_Template_Exception(sprintf(
                    'Specified template layout not found. Expected to find "%s" in Willow_Template::setLayout()',
                    Willow_Loader::buildPath($dataPath, 'html')
                ));
            }
        }
        else
        {
            $layout = $dataPath;
        }

        $this->_layout = $layout;
    }

    /**
     * Build the template layout
     */
    protected function _buildLayout($content)
    {
		$this->_engine->setTemplate($this->_layout);

        if (($page = $this->_engine->getVar('page')) === false)
        {
            $page = array();
        }

        $page['content'] = $content;

		$this->_engine->setVar('page', $page);

		return $this->_engine->build();
    }

    /**
     * @var array registered template engine(s) map
     */
    protected static $_engineMap = array();

    /**
     * Register a template engine for a given protocol
     *
     * @param string $protocol Protocol to use engine with (default, http, json, xmlhttp, etc)
     * @param Willow_Template_Engine_Interface $engine
     * @throws Willow_Template_Exception When $engine does not implement Willow_Template_Engine_Interface
     * @return void
     */
    public static function register($protocol, $engine)
    {
        $protocol = strtolower($protocol);
        self::$_engineMap[$protocol] = $engine;
    }

    /**
     * Get the engine registered to a protocol
     *
     * @param string $protocol Protcol to get registered engine for
     * @throws Willow_Template_Exception When no engine can be retrieved for given protocol
     * @return Willow_Template_Engine_Interface
     */
    public function getRegistered($protocol)
    {
        $protocol = strtolower($protocol);

        /**
         * If not set for this specific protocol, change to default
         */
        if (!isset(self::$_engineMap[$protocol]))
        {
            $protocol = 'default';
        }

        /**
         * Cannot be found!
         */
        if (!isset(self::$_engineMap[$protocol]))
        {
            throw new Willow_Template_Exception(sprintf(
                'No template engine registered to %s', $protocol
            ));
        }

        /**
         * Class name registered
         */
        if (is_string(self::$_engineMap[$protocol]))
        {
            $engine = new self::$_engineMap[$protocol]();

            /**
             * Engines must implement Willow_Template_Engine_Interface
             */
            if (($engine instanceof Willow_Template_Engine_Interface) === false)
            {
                throw new Willow_Template_Exception(sprintf(
                    'Template engine registered to %s must implement Willow_Template_Engine_Interface',
                    $protocol
                ));
            }

            /**
             * Set engine instance back to map
             */
            self::$_engineMap[$protocol] = $engine;
        }

        return self::$_engineMap[$protocol];
    }

    /**
     * ...
     */
    public function getTemplate()
    {
        if (($template = $this->_engine->getTemplate()) === null)
        {
            return false;
        }

        return $template;
    }

    /**
     * Handoff requests to the template engine
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_engine, $method), $args);
    }

    /**
     * Shortcut to Willow_Template_Engine_Interface::getVar()
     */
    public function __get($var)
    {
        return $this->_engine->getVar($var);
    }

    /**
     * Shortcut to Willow_Template_Engine_Interface::setVar
     */
    public function __set($var, $value = null)
    {
        $this->_engine->setVar($var, $value);
    }

}
