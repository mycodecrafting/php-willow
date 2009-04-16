<?php

class Willow_Autoloader_Fallback extends Willow_Autoloader_Abstract
{

    public function autoload($className)
    {
        $file = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        @include_once $file;
    }

}
