<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Plexus \Plex"us\, n. An intricate or interwoven combination of elements or parts 
 * in a coherent structure.
 */
class Willow_Plexus
{

    /**
     * ...
     */
    protected $_pluginHandler = null;

    /**
     * ...
     */
    public function __construct(Willow_Plugin_Handler $pluginHandler = null)
    {
        if ($pluginHandler === null)
        {
            $pluginHandler = new Willow_Plugin_Handler();
        }

        $this->_pluginHandler = $pluginHandler;
        $this->_pluginHandler->setPlexus($this);
    }

    /**
     * ...
     */
    protected function _plexusCall($method, array $args)
    {
		$return = null;

		$baseObject = $this->_getBaseObject($args);

		$pluginMethod = $this->_getPluginMethod($baseObject, $method);


		// before hook - runs before method; allows parameters to be changed
		$this->_callBeforeHook($baseObject, $pluginMethod, $args);


		// around hook
		if ($this->_callAroundHook($baseObject, $method, $pluginMethod, $args, $return) !== false)
		{

			// on hook
			$this->_callOnHook($pluginMethod, $args, $return);
		}


		// after hook
		$this->_callAfterHook($pluginMethod, $args);


		return $return;
    }

    /**
     * ...
     */
	protected function _callBeforeHook($baseObject, $pluginMethod, array &$args)
	{
	    $pluginMethod = 'before' . $pluginMethod;

        $objectArgs = $args;
        array_shift($objectArgs);

        $args[] =& $objectArgs;

		call_user_func_array(array($this->_pluginHandler, $pluginMethod), $args);

        $args = $objectArgs;
		array_unshift($args, $baseObject);
	}

    /**
     * ...
     */
	protected function _callAroundHook($baseObject, $method, $pluginMethod, array $args, &$return)
	{
		$canRun = true;

		if (method_exists($baseObject, $method) || method_exists($baseObject, '__call'))
		{
			// callback surrounds the method execution
			$pluginMethod = 'around' . $pluginMethod;
			$canRun = call_user_func_array(array($this->_pluginHandler, $pluginMethod), $args);

			// method is allowed to run
			if ($canRun === true)
			{
				$objectArgs = $args;
				array_shift($objectArgs);
				$return = call_user_func_array(array($baseObject, $method), $objectArgs);

			// have replaced the method
			}
			elseif ($canRun !== false)
			{
				$return = $canRun;
			}
		}

		return $canRun;
	}

    /**
     * ...
     */
	protected function _callOnHook($pluginMethod, array $args, &$return)
	{
	    $pluginMethod = 'on' . $pluginMethod;
		$onArgs = $args;
		$onArgs[] =& $return;
		call_user_func_array(array($this->_pluginHandler, $pluginMethod), $onArgs);
	}

    /**
     * ...
     */
	protected function _callAfterHook($pluginMethod, array $args)
	{
	    $pluginMethod = 'after' . $pluginMethod;
		call_user_func_array(array($this->_pluginHandler, $pluginMethod), $args);
	}

    /**
     * Returns the plugin method that will be called as hooks
     *
     * Example:     Module_SectionAction_Actions calls Willow_Plexus::doDoAction =>
     *              beforeModuleSectionActionActionsDoDoAction
     *              aroundModuleSectionActionActionsDoDoAction
     *              onModuleSectionActionActionsDoDoAction
     *              afterModuleSectionActionActionsDoDoAction
     */
	protected function _getPluginMethod($baseObject, $method)
	{
		return $this->_normalize(sprintf('%s_%s', get_class($baseObject), $method));
	}

    /**
     * ...
     */
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

    /**
     * Normalize strings-with-dashes_or_underscores to StringsWithCamelCase
     */
    protected function _normalize($string)
    {
        return Willow::utils()->string($string)->toCamelCase($lower = false);
    }

    /**
     * ...
     */
	public function __call($method, array $args)
	{
		if (strpos($method, 'do') === 0)
		{
			return $this->_plexusCall(strtolower($method[2]) . substr($method, 3), $args);
		}
	}

}
