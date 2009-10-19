<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Plugin_Loader
{

    /**
     * @var array Plugin types & loading order
     */
    protected static $_pluginTypes = array(
        'Core', 'Data', 'TemplateEngine', 'Addons'
    );

    public static function loadAll($exceptions = array())
    {
        foreach (self::$_pluginTypes as $pluginType)
        {
            self::loadType($pluginType, $exceptions);
        }
    }


    public static function loadType($type, $exceptions = array())
    {
        $bootstrapping = array();

        /**
         * Setup the plugin paths
         */
        $pluginPaths = array(
            // core framework path
            'Vendor:Willow:Plugin:%s',

            // vendor path
            'Vendor:Plugin:%s',

            // app path
            'App:Plugin:%s',

            // deployment path
            'Deployment:Plugin:%s',
        );

        /**
         * Get plugins of this type in any of the plugin paths
         */
        foreach ($pluginPaths as $path)
        {
            foreach (self::_getPluginsInPath(sprintf($path, $type)) as $plugin)
            {
                $dataPath = sprintf('%s:%s', $type, $plugin);

                /**
                 * Plugin is excluded
                 */
                if (in_array($dataPath, $exceptions))
                {
                    continue;
                }

                /**
                 * Register an autoloader for plugin
                 */
                Willow_Autoloader::register(
                    new Willow_Plugin_Autoloader($dataPath)
                );

                /**
                 * Plugin has a bootstrap
                 */
                if (($bootstrap = Willow_Loader::getRealPath(sprintf('Plugin:%s:Willow:Bootstrap', $dataPath))) !== false)
                {
                    $bootstrapping[] = $bootstrap;
                }
            }
        }

        /**
         * Bootstrap plugins
         */
        foreach ($bootstrapping as $bootstrap)
        {
            require $bootstrap;
        }
    }

    /**
     * ...
     */
    protected static $_configs;

    /**
     * ...
     */
    public static function getConfigs($exceptions = array())
    {
        if (!isset(self::$_configs))
        {
            self::$_configs = array();

            foreach (self::$_pluginTypes as $pluginType)
            {
                self::_checkForConfigs($pluginType, $exceptions);
            }
        }

        return self::$_configs;
    }

    protected static function _checkForConfigs($type, $exceptions = array())
    {
        /**
         * Setup the plugin paths
         */
        $pluginPaths = array(
            // core framework path
            'Vendor:Willow:Plugin:%s',

            // vendor path
            'Vendor:Plugin:%s',

            // app path
            'App:Plugin:%s',

            // deployment path
            'Deployment:Plugin:%s',
        );

        /**
         * Get plugins of this type in any of the plugin paths
         */
        foreach ($pluginPaths as $path)
        {
            foreach (self::_getPluginsInPath(sprintf($path, $type)) as $plugin)
            {
                $dataPath = sprintf('%s:%s', $type, $plugin);

                /**
                 * Plugin is excluded
                 */
                if (in_array($dataPath, $exceptions))
                {
                    continue;
                }

                /**
                 * Plugin has a config
                 */
                if (($config = Willow_Loader::getRealPath(sprintf('Plugin:%s:Willow:Config:Willow', $dataPath), false, 'yml')) !== false)
                {
                    self::$_configs[] = $config;
                }
            }
        }
    }











    protected static function _getPluginsInPath($dataPath)
    {
        if (($path = Willow_Loader::getRealPath($dataPath, false, false)) === false)
        {
            return array();
        }

        $plugins = array();

        foreach (scandir($path) as $file)
        {
            /**
             * Plugin directories have a "plugin" extension
             */
            if (substr($file, -7) !== '.plugin')
            {
                continue;
            }

            $plugins[] = substr($file, 0, -7);
        }

        return $plugins;
    }

}
