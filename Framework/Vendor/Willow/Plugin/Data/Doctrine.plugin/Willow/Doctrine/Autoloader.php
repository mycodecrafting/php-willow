<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Doctrine_Autoloader extends Willow_Autoloader_Abstract
{

    public function autoload($className)
    {
        if (strpos($className, 'Doctrine') === 0)
        {
            require str_replace('_', DS, $className) . '.php';
        }
        elseif (strpos($className, 'Base') === 0)
        {
            if (($file = Willow_Loader::getRealPath(sprintf('App:Models:generated:%s', $className))) !== false)
            {
                require $file;
            }
        }
        elseif (($file = Willow_Loader::getRealPath(sprintf('App:Models:%s', $className))) !== false)
        {
            require $file;
        }
    }

}
