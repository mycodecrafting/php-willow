<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Mailchimp_Autoloader extends Willow_Autoloader_Abstract
{

    public function autoload($className)
    {
        if ($className === 'MCAPI')
        {
            Willow_Loader::loadFile('Vendor:Mailchimp:MCAPI.class');
        }
    }

}
