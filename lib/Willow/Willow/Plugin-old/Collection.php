<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Plugin_Collection
{

	protected $_plugins = array();

	/**
	 * @throws Exception when encounters plugin not of class Willow_Plugin.
	 */
	public function __construct(array $plugins)
	{
		foreach ($plugins as $plugin)
		{
			// another plugin collection passed in
			if (($plugin instanceof PluginCollection) === true)
			{
				$this->plugins = array_merge($this->plugins, $plugin->plugins);
				continue;
			}

			if (is_object($plugin) === false)
			{
				$plugin = new $plugin();				
			}

			if (($plugin instanceof Plugin) === false)
			{
				$class = get_class($plugin);
				if (!($parent = get_parent_class($plugin)))
				{
					$parent = $class;
				}

				throw new Exception(
					'Expected "' . $class . '" to be of class Plugin; was "' .
					$parent . '" instead.'
				);
			}

			$this->plugins[get_class($plugin)] = $plugin;
		}
	}

	protected function dispatchMethod($method, $args)
	{
		$return = null;

		$baseObject = array_shift($args);

		foreach ($this->plugins as $plugin)
		{
			if (!method_exists($plugin, $method) && ($plugin->overloadHooks === false))
			{
				continue;
			}

			// If a plugin is dispatching don't change the baseObject.
			// If another plugin needs to access the dispatching plugin,
			//   it can use $this->plexus->PluginName.
			if (($baseObject instanceof Plugin) === false)
			{
				$plugin->loadBaseObject($baseObject);
				$plugin->loadPlexus($baseObject->plexus);
			}

			$tmp_return = call_user_func_array(array($plugin, $method), $args);

			if ($tmp_return !== null)
			{
				$return = $tmp_return;
			}
		}

		return $return;	
	}

	private function __get($property)
	{
		if (isset($this->plugins[$property]))
		{
			return $this->plugins[$property];
		}

		return false;
	}

	private function __call($method, array $args)
	{
		$return = $this->dispatchMethod($method, $args);

		if (strpos($method, 'around') === 0)
		{
			if ($return === null)
			{
				$return = true;
			}
			elseif ($return === Plugin::REPLACED_METHOD)
			{
				$return = null;
			}
		}

		return $return;
	}

}
