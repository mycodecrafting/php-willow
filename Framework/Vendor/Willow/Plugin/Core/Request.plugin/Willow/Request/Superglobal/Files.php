<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Request_Superglobal_Files extends Willow_Request_Abstract
{

    public function __construct()
    {
        foreach ($_FILES as $key => $data)
        {
            $this->import(array($key => new Willow_Request_Array($data)));
        }
    }

}
