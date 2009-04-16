<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Application
{

    /**
     * Prevent public construction
     */
    private function __construct()
    {
    }

    /**
     * Dispatch the application
     */
    public function dispatch()
    {
        /**
         * Make sure a request was set
         * @todo generate default if not set
         */
        if ($this->getRequest() === null)
        {
            throw new Willow_Application_Exception(
                'Must set the request prior to dispatching the application'
            );
        }

        /**
         * Make sure a router was set
         * @todo generate default if not set
         */
        if ($this->getRouter() === null)
        {
            throw new Willow_Application_Exception(
                'Must set the router prior to dispatching the application'
            );
        }

        /**
         * Set request for router
         */
        $this->getRouter()->setRequest($this->getRequest());

        /**
         * Perform routing
         */
        $this->getRouter()->route();

        /**
         * Delegate to appropriate app controller
         *
         * @todo dynamic (registerable?) generation of app controller per protocol
         */
        $this->_getAppController()->execute();
    }

    /**
     * @var Willow_Request_Interface
     */
    private $_request = null;

    /**
     * Retrieve the set request
     *
     * @return Willow_Request_Interface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Set the request
     *
     * @param Willow_Request_Interface $request
     * @return void
     */
    public function setRequest(Willow_Request_Interface $request)
    {
        $this->_request = $request;
    }

    /**
     * @var Willow_Request_Router_Interface
     */
    private $_router;

    /**
     * Retrieve the set router
     *
     * @return Willow_Request_Router_Interface
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * Set the router
     *
     * @param Willow_Request_Router_Interface $router
     * @return void
     */
    public function setRouter(Willow_Request_Router_Interface $router)
    {
        $this->_router = $router;
    }

    /**
     * Get app controller to delegate request to
     *
     * @return Willow_Application_Abstract
     */
    protected function _getAppController()
    {
        $class = sprintf('Willow_%s_Application', $this->getRequest()->getProtocol());
        return new $class($this->getRequest());
    }

    /**
     * @var Willow_Application Instance of self
     */
    private static $_instance = null;

    /**
     * Get current instance of Willow_Application
     *
     * @return Willow_Application
     */
    public static function getInstance()
    {
        if ((self::$_instance instanceof Willow_Application) === false)
        {
            self::$_instance = new Willow_Application();
        }

        return self::$_instance;
    }

}
