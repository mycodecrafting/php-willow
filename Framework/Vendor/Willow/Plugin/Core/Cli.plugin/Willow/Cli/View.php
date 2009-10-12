<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Cli_View extends Willow_View_Abstract
{

    /**
     * Constructor
     */
    public function __construct(Willow_Request_Interface $request)
    {
        $this->_request = $request;

        /**
         * @todo
         */
        $this->_plexus = new Willow_Plexus();
    }

    /**
     * Style CLI output
     */
    protected $_styles = array(
		'off'       => "\033[0m",
		'bold'      => "\033[1m",
		'b'         => "\033[1m",
		'underline' => "\033[4m",
		'u'         => "\033[4m",

		// foreground colors
		'black'     => "\033[30m",
		'red'       => "\033[31m",
		'green'     => "\033[32m",
		'yellow'    => "\033[33m",
		'blue'      => "\033[34m",
		'magenta'   => "\033[35m",
		'cyan'      => "\033[36m",
		'white'     => "\033[37m",

		// background colors
		'blackbg'   => "\033[40m",
		'redbg'     => "\033[41m",
		'greenbg'   => "\033[42m",
		'yellowbg'  => "\033[43m",
		'bluebg'    => "\033[44m",
		'magentabg' => "\033[45m",
		'cyanbg'    => "\033[46m",
		'whitebg'   => "\033[47m",
    );

    /**
     * ...
     */
    public function update($notification)
    {
        if (method_exists($this, $notification))
        {
            $args = array();

            if (func_num_args() > 1)
            {
                $args = func_get_args();
                array_shift($args);
            }

    		call_user_func_array(array($this, $notification), $args);
        }
    }

    /**
     * Send output to screen/console/log
     */
    public function out($message = '', $resetAfter = true)
    {
        if ($resetAfter === true)
        {
            $message .= '[off]' . NL;
        }

        echo preg_replace_callback('/\[(\w+)\]/', array($this, '_formatOutputFromPcre'), $message);
    }

    /**
     * ...
     */
	public function error($message)
	{
	    $this->out("[red][bold]ERROR: $message");
	}

    /**
     * ...
     */
    public function success($message)
    {
        $this->out("[green][bold]$message");
    }

    /**
     * Clear the screen
     */
    public function clear()
    {
        echo "\033[1;1H\033[2J";
    }

    /**
     * Sound a beep
     */
    public function beep()
    {
        echo "\007";
    }

    /**
     * ...
     */
    protected function _formatOutputFromPcre($matches)
    {
        if (isset($this->_styles[$matches[1]]))
        {
            return $this->_styles[$matches[1]];
        }

        return $matches[0];
    }

    /**
     * ...
     */
    public function preGenerate()
    {
    }

    /**
     * ...
     */
    public function generate()
    {
    }

}
