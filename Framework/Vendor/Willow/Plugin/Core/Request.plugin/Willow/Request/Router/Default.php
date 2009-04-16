<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Default extends Willow_Request_Router_Abstract
{

    /**
     * Perform routing
     *
     * @todo use routes config & parameter injection
     */
    public function route()
    {
        $route = new Willow_Request_Router_Route_Default();

        if (($params = $route->match($this->getRequest())) !== false)
        {
            $this->_setParams($params);
            $this->_currentRoute = 'default';
        }
    }

}
