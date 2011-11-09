<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Request_Router_Abstract implements Willow_Request_Router_Interface
{

    /**
     * @var Willow_Request_Interface
     */
    protected $_request;

    /**
     * Set the request
     *
     * @param Willow_Request_Interface $request
     */
    public function setRequest(Willow_Request_Interface $request)
    {
        $this->_request = $request;
    }

    /**
     * Get the request
     *
     * @return Willow_Request_Interface
     */
    public function getRequest()
    {
        return $this->_request;
    }

    protected $_routes = array();

    protected $_currentRoute;

    public function hasRoute($name)
    {
        return isset($this->_routes[$name]);
    }

    public function addRoute($name, Willow_Request_Router_Route_Interface $route)
    {
        $this->_routes[$name] = $route;
    }

    public function getCurrentRoute()
    {
        return $this->_currentRoute;
    }

    protected function _setParams($params)
    {
        /**
         * Set GET params
         */
        if (isset($params['params']))
        {
            $this->getRequest()->get()->import($params['params']);
        }

        /**
         * Set module name
         */
        if (isset($params['module']))
        {
            $this->getRequest()->setModule($params['module']);
        }

        /**
         * Set section name
         */
        if (isset($params['section']))
        {
            $this->getRequest()->setSection($params['section']);
        }

        /**
         * Set action name
         */
        if (isset($params['action']))
        {
            $this->getRequest()->setAction($params['action']);
        }
    }

}
