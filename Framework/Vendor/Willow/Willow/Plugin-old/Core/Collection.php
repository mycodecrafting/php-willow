<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Core plugin collection
 */
class Willow_Plugin_Core_Collection extends PluginCollection
{



}



    class FlexieCorePluginCollection extends PluginCollection
    {

        public $pluginInfo = array();
        protected $corePaths = array();

        public function __construct()
        {
            $this->corePaths = func_get_args();

            foreach ($this->getPlugins() as $plugin)
            {
                // create plugin settings object using default settings
                $settings = $plugin->defaultSettings;

                // include plugin
                require ($plugin->path . DS . 'Plugin.php');

                $class = $plugin->name . $plugin->group;
                $this->plugins[$plugin->name] = new $class($settings);;
                $this->pluginInfo[$plugin->name] = $plugin;

                // set the plugin dictionary
                if (file_exists($plugin->path . DS . 'Resource' . DS . 'Dictionary.yml'))
                {
                    $dictionary = new FlexieYamlNode(
                        $plugin->path . DS . 'Resource' . DS . 'Dictionary.yml'
                    );

                    $this->plugins[$plugin->name]->setDictionary($dictionary);
                }
            }
        }


        protected function getPlugins()
        {
            $plugins = array();
            $sort = array();
            $name = array();

            // core plugins are always installed; read from directories
            foreach ($this->corePaths as $path)
            {
                foreach (scandir($path) as $file)
                {
                    if (substr($file, -7) !== '.plugin')
                    {
                        continue;
                    }

                    // plugin already found in one of the preferred paths
                    if (in_array($file, $name))
                    {
                        continue;
                    }

                    // set path to plugin main class
                    $pluginPath = $path . DS . $file;

                    // include plugin Init script if it exists
                    if (file_exists($pluginPath . DS . 'Flexie' . DS . 'Init.php'))
                    {
                        require ($pluginPath . DS . 'Flexie' . DS . 'Init.php');
                    }

                    // Plugin.php or Plugin.yml is missing;
                    // Do not add to plugin list
                    if (!file_exists($pluginPath . DS . 'Plugin.php') ||
                        !file_exists($pluginPath . DS . 'Plugin.yml')
                    ) {
                        continue;
                    }

                    // read plugin info file
                    $info = new FlexieYamlNode($pluginPath . DS . 'Plugin.yml');

                    // add path to info
                    $info->addKeyValuePair('path', $pluginPath);

                    $plugins[] = $info;
                    $sort[] = $info->sortOrder;
                    $name[] = $file;
                }
            }

            // sort on sort order; then on name for any with the same sort order
            array_multisort($sort, SORT_ASC, SORT_NUMERIC, $name, SORT_ASC, SORT_STRING, $plugins);

            return $plugins;
        }

    }

