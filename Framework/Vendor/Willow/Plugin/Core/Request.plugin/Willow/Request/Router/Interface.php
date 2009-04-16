<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_Request_Router_Interface
{

    /**
     * Set the request
     *
     * @param Willow_Request_Interface $request
     */
    public function setRequest(Willow_Request_Interface $request);

    /**
     * Get the request
     *
     * @return Willow_Request_Interface
     */
    public function getRequest();

    public function hasRoute($name);

    public function addRoute($name, Willow_Request_Router_Route_Interface $route);

    /**
     * Perform routing
     */
    public function route();

}
