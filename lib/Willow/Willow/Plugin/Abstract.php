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
	public $_plexus = null;

	/**
	 * @var object Object The current object being plugged into.
	 */
	protected $_baseObject = null;

    /**
     * ...
     */
	const REPLACED_METHOD = 'Indicates that a method with void return has been replaced';

	/**
	 * Load the current object being plugged into.
	 * @param object $object The current object being plugged into.
	 */
	public function setBaseObject($object)
	{
		$this->_baseObject = $object;
	}

    /**
     * ...
     */
    public function getBaseObject()
    {
        return $this->_baseObject;
    }

	/**
	 * Load the current {@link Willow_Plexus} that has loaded this Plugin.
	 * @param Plexus $object The current {@link Willow_Plexus} that has loaded this Plugin.
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
    public function __call($method, array $args)
	{
		if ((strpos($method, 'do') === 0) && ($this->getPlexus() !== null))
		{
			array_unshift($args, $this);
			$return = call_user_func_array(array($this->getPlexus(), $method), $args);
			return $return;
		}
	}

}
