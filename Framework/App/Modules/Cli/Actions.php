<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Cli_Actions extends Willow_Cli_Actions
{

    /**
     * ...
     */
    abstract public function doCommand();

    /**
     * ...
     */
    public function doAction()
    {
        /**
         * User requested help
         */
        if ($this->getRequest()->argv()->sanitized()->asBoolean()->help === true)
        {
            return $this->notifyView('help');
        }

        /**
         * Do input validation
         */
        if ($this->doValidation() === false)
        {
            return;
        }

        /**
         * Validation passed; run command
         */
        return $this->doCommand();
    }

    /**
     * ...
     */
    public function doValidation()
    {
        return true;
    }

}
