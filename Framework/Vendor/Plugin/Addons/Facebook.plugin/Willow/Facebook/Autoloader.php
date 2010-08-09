<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Facebook_Autoloader extends Willow_Autoloader_Abstract
{

    public function autoload($className)
    {
        if (($className === 'Facebook') || ($className === 'FacebookApiException'))
        {
            Willow_Loader::loadFile('Vendor:Facebook:Facebook');
        }
    }

}
