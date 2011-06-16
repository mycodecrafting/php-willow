<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Doctrine_Autoloader extends Willow_Autoloader_Abstract
{

    public function autoload($className)
    {
        /**
         * Doctrine_Core
         */
        if ($className === 'Doctrine_Core')
        {
            require 'Doctrine/Core.php';
        }

        /**
         * If this is a doctrine class, call the Doctrine autoloader
         */
        elseif ((strpos($className, 'Doctrine') === 0) || (strpos($className, 'sfYaml') === 0))
        {
            return Doctrine_Core::autoload($className);
        }

        /**
         * Base model requested
         */
        elseif (strpos($className, 'Base') === 0)
        {
            if (($file = Willow_Loader::getRealPath(sprintf('App:Models:generated:%s', $className))) !== false)
            {
                require $file;
            }
        }

        /**
         * Check if it's a model
         */
        elseif (($file = Willow_Loader::getRealPath(sprintf('App:Models:%s', $className))) !== false)
        {
            require $file;
        }
    }

}
