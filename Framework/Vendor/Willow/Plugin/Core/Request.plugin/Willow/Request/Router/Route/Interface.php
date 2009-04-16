<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_Request_Router_Route_Interface
{

    public function match(Willow_Request_Interface $request);

}