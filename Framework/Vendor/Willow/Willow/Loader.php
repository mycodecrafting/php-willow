<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Loader
{

    protected static $_pathCache = array();

    public static function loadFile($dataPath, $once = true, $overridable = true)
    {
        /**
         * Load once and already loaded
         */
        if (($once === true) && (array_key_exists($dataPath, self::$_pathCache) === true))
        {
            return;
        }

        /**
         * Get the real path from the data path
         */
        if (($path = self::getRealPath($dataPath, $overridable, 'php')) === false)
        {
            throw new Willow_DataPath_Exception(
                sprintf('Could not translate data path "%s" to real path', $dataPath),
                Willow_DataPath_Exception::ERR_REQUIRE
            );
        }

        /**
         * Load file
         */
        require $path;

        /**
         * Set in cache
         */
        self::$_pathCache[$dataPath] = $path;
    }


    protected static $_dataPaths = null;

    /**
     * Get the real filesystem path from the framework data path
     */
    public static function getRealPath($dataPath, $overridable = true, $ext = 'php')
    {
        if (function_exists('apc_add') === false || function_exists('posix_getpid') === false)
        {
            return self::_getRealPath($dataPath, $overridable, $ext);
        }

        if (self::$_dataPaths === null)
        {
            $cacheKey = md5(implode('|', array('datapaths', Willow::getRoot(), Willow::getAppDir(), Willow::getDeployment(), posix_getpid())));

            self::$_dataPaths = array();

            if (apx_exists($cacheKey))
            {
                self::$_dataPaths = apc_fetch($cacheKey);
            }
        }

        $dataPathKey = md5(implode('|', array($dataPath, $overridable, $ext)));

        if (!isset(self::$_dataPaths[$dataPathKey]))
        {
            self::$_dataPaths[$dataPathKey] = self::_getRealPath($dataPath, $overridable, $ext);

            $cacheKey = md5(implode('|', array('datapaths', Willow::getRoot(), Willow::getAppDir(), Willow::getDeployment(), posix_getpid())));

            apc_store($cacheFile, self::$_dataPaths, 86400);
        }

        return self::$_dataPaths[$dataPathKey];
    }

    /**
     * Get the real filesystem path from the framework data path
     */
    public static function _getRealPath($dataPath, $overridable = true, $ext = 'php')
    {
        $dataPathArray = explode(':', $dataPath);

        if (count($dataPathArray) < 2)
        {
            return false;
        }

        $pathBase = array_shift($dataPathArray);

        /**
         * Search out file
         */
        switch ($pathBase)
        {
            /**
             * Core application (or framework file)
             */
            case 'App':
                /**
                 * Depployment overriding
                 */
                if ($overridable === true)
                {
                    if (($path = self::isOverriden($dataPathArray)) !== false)
                    {
                        return $path;
                    }
                }

                /**
                 * Check the App directory
                 */
                $path = self::buildPath($dataPath, $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                /**
                 * Check in the core Willow framework
                 */
                return self::getRealPath(
                    sprintf('Core:%s', implode(':', $dataPathArray)), $overridable, $ext
                );
                break;

            /**
             * Core framework
             */
            case 'Core':
                /**
                 * Depployment overriding
                 */
                if ($overridable === true)
                {
                    if (($path = self::isOverriden($dataPathArray)) !== false)
                    {
                        return $path;
                    }
                }

                /**
                 * Check in the core Willow framework
                 */
                $path = self::buildPath(
                    sprintf('Vendor:Willow:Willow:%s', implode(':', $dataPathArray)), $ext
                );

                if (file_exists($path))
                {
                    return $path;
                }

                return false;
                break;

            /**
             * Checking for file in this deployment
             */
            case 'Deployment':
                $path = self::buildPath(
                    sprintf('Deployment:%s:%s', Willow::getDeployment(), implode(':', $dataPathArray)), $ext
                );

                if (file_exists($path))
                {
                    return $path;
                }

                return false;
                break;

            /**
             * Loading a plugin file
             */
            case 'Plugin':
                $pluginType = array_shift($dataPathArray);
                $pluginName = array_shift($dataPathArray);

                $pluginDataPath = sprintf(
                    'Plugin:%s:%s.plugin:%s', $pluginType, $pluginName, implode(':', $dataPathArray)
                );

                /**
                 * Depployment overriding
                 */
                if ($overridable === true)
                {
                    if (($path = self::isOverriden($pluginDataPath)) !== false)
                    {
                        return $path;
                    }
                }

                /**
                 * Check for vendor plugin
                 */
                $path = self::buildPath('Vendor:' . $pluginDataPath, $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                /**
                 * Check for app plugin
                 */
                $path = self::buildPath('App:' . $pluginDataPath, $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                /**
                 * Check for willow framework plugin
                 */
                $path = self::buildPath('Vendor:Willow:' . $pluginDataPath, $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                return false;
                break;

            /**
             * Checking for vendor file
             */
            case 'Vendor':
                /**
                 * Depployment overriding
                 */
                if ($overridable === true)
                {
                    if (($path = self::isOverriden($dataPathArray)) !== false)
                    {
                        return $path;
                    }
                }

                /**
                 * Check default location
                 */
                $path = self::buildPath('Vendor:' . implode(':', $dataPathArray), $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                return false;
                break;

            default:
                /**
                 * Deployment overriding
                 */
                if (($overridable === true) && (($path = self::isOverriden($dataPath)) !== false))
                {
                    return $path;
                }

                /**
                 * Check default location
                 */
                $path = self::buildPath($dataPath, $ext);

                if (file_exists($path))
                {
                    return $path;
                }

                return false;
                break;
        }
    }

    /**
     * ...
     */
    public static function appendIncludePath()
    {
        $dataPaths = func_get_args();

        $paths = array();

        foreach ($dataPaths as $dataPath)
        {
            $paths[] = self::getRealPath($dataPath, false, false);
        }

        set_include_path(
            implode(PATH_SEPARATOR, $paths) .
            PATH_SEPARATOR . get_include_path()
        );
    }

    /**
     * ...
     */
    public static function buildPath($dataPath, $ext = false)
    {
        $dataPath = explode(':', $dataPath);
        foreach ($dataPath as $i => $part)
        {
            if ($part === 'App')
            {
                $dataPath[$i] = Willow::getAppDir();
            }
        }

        $path = Willow::getRoot() . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $dataPath);

        if ($ext !== false)
        {
            $path .= '.' . $ext;
        }

        return $path;
    }

    /**
     * ...
     */
    public static function isOverriden($dataPath)
    {
        if (is_array($dataPath))
        {
            $dataPath = implode(':', $dataPath);
        }

        return self::getRealPath('Deployment:' . $dataPath);
    }

}
