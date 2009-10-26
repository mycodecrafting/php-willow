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

    /**
     * ...
     */
    public function set($property, $value, $expires = 0, $path = '/')
    {
        $this->$property = $value;
        setcookie($property, $value, strtotime($expires), $path);
        return $this;
    }

    /**
     * ...
     */
    public function delete($property, $path = '/')
    {
        $this->set($property, '', '-30 Days');
        unset($this->$property);
        return $this;
    }

}
