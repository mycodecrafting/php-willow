<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Cli extends Willow_Request_Router_Abstract
{

    /**
     * Perform routing
     */
    public function route()
    {
        $route = new Willow_Request_Router_Route_Cli();

        if (($params = $route->match($this->getRequest())) !== false)
        {
            $this->_setParams($params);
            $this->_currentRoute = 'cli';
        }
    }

}
