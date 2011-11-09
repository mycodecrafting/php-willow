<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Autoloader_Abstract
{

    protected $_dataPath;

    public function __construct($dataPath = false)
    {
        $this->_dataPath = $dataPath;
    }

    protected function _loadFile($dataPath, $overridable = true)
    {
        if ($this->_dataPath !== false)
        {
            $dataPath = $this->_dataPath . ':' . $dataPath;
        }

        Willow_Loader::loadFile($dataPath, $once = true, $overridable);
    }

    abstract public function autoload($className);

}
