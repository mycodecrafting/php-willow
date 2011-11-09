<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Plugin_Handler implements Willow_Registerable_Interface
{

    /**
     * ...
     */
    protected $_plexus = null;

    /**
     * ...
     */
    public function setPlexus(Willow_Plexus $plexus)
    {
        $this->_plexus = $plexus;
    }

    /**
     * ...
     */
    public function getPlexus()
    {
        return $this->_plexus;
    }

    /**
     * ...
     */
    protected static $_plugins = array();

    /**
     * Register a plugin under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $plugin)
    {
        if (is_string($plugin))
        {
            $plugin = new $plugin();

            /**
             * Plugins must extend Willow_Plugin_Abstract
             */
            if (($plugin instanceof Willow_Plugin_Abstract) === false)
            {
                throw new Willow_Plugin_Exception(sprintf(
                    'Plugin %s registered to %s must extend Willow_Plugin_Abstract',
                    get_class($plugin),
                    $alias
                ));
            }
        }

        self::$_plugins[$alias] = $plugin;
    }

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return object
     * @throws Exception
     */
    public function getRegistered($alias)
    {
        if (isset(self::$_plugins[$alias]))
        {
            return self::$_plugins[$alias];
        }
    }

    /**
     * ...
     */
	protected function _dispatchMethod($method, $args)
	{
		$return = null;

		$baseObject = array_shift($args);

		foreach (self::$_plugins as $plugin)
		{
			if (!method_exists($plugin, $method) && ($plugin->overloadHooks === false))
			{
				continue;
			}

			// If a plugin is dispatching don't change the baseObject.
			// If another plugin needs to access the dispatching plugin,
			//   it can use $this->plexus->PluginName.
			if (($baseObject instanceof Willow_Plugin_Abstract) === false)
			{
				$plugin->setBaseObject($baseObject);
				$plugin->setPlexus($this->getPlexus());
			}

			$tmpReturn = call_user_func_array(array($plugin, $method), $args);

			if ($tmpReturn !== null)
			{
				$return = $tmpReturn;
			}
		}

		return $return;	
	}

    /**
     * ...
     */
	public function __call($method, array $args)
	{
		$return = $this->_dispatchMethod($method, $args);

		if (strpos($method, 'around') === 0)
		{
			if ($return === null)
			{
				$return = true;
			}
			elseif ($return === Willow_Plugin_Abstract::REPLACED_METHOD)
			{
				$return = null;
			}
		}

		return $return;
	}

}
