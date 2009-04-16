<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Rewrite extends Willow_Request_Router_Abstract
{

    public function __construct(Willow_Yaml_Node $routes = null)
    {
        /**
         * Add default routing
         */
        $this->addDefaultRoutes();

        if ($routes !== null)
        {
            foreach ($routes as $name => $route)
            {
                $this->addRoute($name, new Willow_Request_Router_Route($route));
            }
        }
    }

    /**
     * Perform routing
     */
    public function route()
    {
        /**
         * Process each route until a match is found
         */
        foreach (array_reverse($this->_routes) as $name => $route)
        {
            if (($params = $route->match($this->getRequest())) !== false)
            {
                $this->_setParams($params);
                $this->_currentRoute = $name;
                break;
            }
        }
    }

    public function addDefaultRoutes()
    {
        if ($this->hasRoute('default') === false)
        {
            $this->addRoute('default', new Willow_Request_Router_Route_Default());
        }
    }

}
