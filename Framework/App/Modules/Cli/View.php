<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Cli_View extends Willow_Cli_View
{

    /**
     * ...
     */
    public function beforeActionsRun()
    {
		$this->out('[bold]Willow');
		$this->out('CLI Utilities');
    }

}
