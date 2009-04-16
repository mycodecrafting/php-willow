<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Plexus
{

    protected function _plexusCall($method, array $args)
    {
		$baseObject = $this->_getBaseObject($args);
		$pluginMethod = $this->_getPluginMethod($baseObject, $method);

		$objectArgs = $args;
		array_shift($objectArgs);
		$return = call_user_func_array(array($baseObject, $method), $objectArgs);

        return $return;
    }

	protected function _getPluginMethod($baseObject, $method)
	{
		return ucfirst(get_class($baseObject)) . ucfirst($method);
	}

	protected function _getBaseObject(array &$args)
	{
		if ((sizeof($args) > 0) && is_object($args[0]))
		{
			$baseObject = $args[0];
		}
		else
		{
			$baseObject = $this;
			array_unshift($args, $baseObject);
		}

		return $baseObject;
	}

	public function __call($method, array $args)
	{
		if (strpos($method, 'do') === 0)
		{
			return $this->_plexusCall(strtolower($method[2]) . substr($method, 3), $args);
		}
	}

}
