<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Request_Superglobal_Argv extends Willow_Request_Abstract
{

    /**
     * Constructor
     */
    public function __construct()
    {
        if ((isset($GLOBALS['argv']) === false) || (is_array($GLOBALS['argv']) === false))
        {
            return;
        }

        /**
         * Build args as array
         */
        $args = array();

        /**
         * Setup the "command" argument
         */
        $args['command'] = false;

        if ($this->_isArg($GLOBALS['argv'][1]) === false)
        {
            $args['command'] = $GLOBALS['argv'][1];
        }

        /**
         * process $GLOBALS['argv']
         */
        foreach ($GLOBALS['argv'] as $i => $arg)
        {
            if ($this->_isArg($arg) === false)
            {
                continue;
            }

            /**
             * $arg is a switch
             */
			if (!isset($GLOBALS['argv'][$i + 1]) || $this->_isArg($GLOBALS['argv'][$i + 1]))
			{
				$args[$this->_getArgName($arg)] = true;
			}

            /**
             * $arg has a value
             */
			else
			{
				$args[$this->_getArgName($arg)] = Willow_Utils::string($GLOBALS['argv'][$i + 1])
				    ->autoType($stripQuotes = true);
			}
        }

        $this->import($args);
    }



    /**
     * Get the name for an arg
     *
     * @param string $arg
     * @return string
     */
    protected function _getArgName($arg)
    {
		$argName = substr($arg, 1);

		if ($this->_isLongArg($arg) === true)
		{
			$argName = substr($arg, 2);
		}

        return str_replace('-', '_', strtolower($argName));
    }

    /**
     * Determine if $arg is an argument
     *
     * @param string $arg The argument to test
     * @return boolean
     */
    protected function _isArg($arg)
    {
        if (strpos($arg, '-') === 0)
        {
            return true;
        }

        return false;
    }

    /**
     * Determine if the arg is a long arg, ie, prefixed with "--"
     *
     * @param string $arg The argument to test
     * @return boolean
     */
    protected function _isLongArg($arg)
    {
        if (strpos($arg, '--') === 0)
        {
            return true;
        }

        return false;
    }

}
