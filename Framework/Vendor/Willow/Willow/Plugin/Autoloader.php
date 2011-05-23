<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Plugin_Autoloader extends Willow_Autoloader_Abstract
{

    public function __construct($dataPath = false)
    {
        $this->_dataPath = 'Plugin:' . $dataPath;
    }

    public function autoload($className)
    {
        try
        {
            $this->_loadFile(str_replace('_', ':', $className));
            return true;
        }
        catch (Willow_DataPath_Exception $e)
        {
            return false;
        }
    }

}
