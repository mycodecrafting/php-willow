<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Request_Superglobal_Cookie extends Willow_Request_Abstract
{

    public function __construct()
    {
        $this->import($_COOKIE);
    }

}
