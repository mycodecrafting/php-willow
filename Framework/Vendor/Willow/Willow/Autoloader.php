<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Autoloader extends Willow_Autoloader_Abstract
{

    public static function register(Willow_Autoloader_Abstract $autoloader)
    {
        spl_autoload_register(array($autoloader, 'autoload'));
    }

    public function autoload($className)
    {
        if (strpos($className, 'Willow_') !== 0)
        {
            return;
        }

        try
        {
            $this->_loadFile('App:' . str_replace('_', ':', substr($className, 7)));
        }
        catch (Willow_DataPath_Exception $e)
        {
        }
    }

}
