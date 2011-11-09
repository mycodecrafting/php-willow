<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Http_Actions extends Willow_Actions_Abstract
{

    protected function _redirect($redirectUri)
    {
        throw new Willow_Http_Redirect($redirectUri);
    }

}
