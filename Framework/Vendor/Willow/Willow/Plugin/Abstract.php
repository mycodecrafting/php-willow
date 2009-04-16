<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Plugin_Abstract
{

	/**
	 * @var boolean Allow this Plugin to overload "hook" calls?
	 */
	public $overloadHooks = false;

	/**
	 * @var Willow_Plexus The current {@link Willow_Plexus} that has loaded this Plugin.
	 */
	public $plexus = null;

	/**
	 * @var object Object The current object being plugged into.
	 */
	protected $_baseObject = null;


	const REPLACED_METHOD = 'Indicates that a method with void return has been replaced';


	/**
	 * Load the current object being plugged into.
	 * @param object $object The current object being plugged into.
	 */
	public function loadBaseObject($object)
	{
		$this->_baseObject = $object;
	}

	/**
	 * Load the current {@link Willow_Plexus} that has loaded this Plugin.
	 * @param Plexus $object The current {@link Willow_Plexus} that has loaded this Plugin.
	 */
	public function loadPlexus(Willow_Plexus $plexus)
	{
		$this->plexus = $plexus;
	}

	private function __call($method, array $args)
	{
		if ((strpos($method, 'do') === 0) && ($this->plexus !== null))
		{
			array_unshift($args, $this);
			$return = call_user_func_array(array($this->plexus, $method), $args);
			return $return;
		}
	}

}
