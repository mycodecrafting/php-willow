<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Cli_Actions extends Willow_Actions_Abstract
{

    /**
     * ...
     */
    public function run()
    {
        /**
         * Calls hooks xxxWillowActionsDoAction
         */
        $this->getPlexus()->doWillowActionsDoAction();

        /**
         * ...
         */
        $this->notifyView('beforeActionsRun');

        /**
         * Run actions
         */
        $this->getPlexus()->doDoAction($this);

        /**
         * ...
         */
        $this->notifyView('afterActionsRun');
    }

    /**
     * Notify view of some change
     */
    public function notifyView($notifier)
    {
        $args = func_get_args();
		call_user_func_array(array($this->getView(), 'update'), $args);
    }

    /**
     * Prompt for input
     */
    public function prompt($key = 'input')
    {
        /**
         * Add a "before{$Key}Prompt" view notification w/ any passed args
         */
        $args = func_get_args();
        $args[0] = sprintf('before%sPrompt', ucfirst($key));

		call_user_func_array(array($this, 'notifyView'), $args);

        /**
         * Get user input
         */
        $userInput = trim(fgets(STDIN));

        /**
         * Add an "after{$Key}Prompt view notification w/ $userInput"
         */
        $args = array(sprintf('after%sPrompt', ucfirst($key)), $userInput);

		call_user_func_array(array($this, 'notifyView'), $args);

        /**
         * Return $userInput as autotyped
         */
        return Willow_Utils::string($userInput)->autoType($stripQuotes = true);
    }

    /**
     * ...
     */
    protected function _redirect($redirectUri)
    {
        throw new Willow_Actions_Exception(
            'Willow_Actions::_redirect() not supported in the Willow Framework CLI plugin'
        );
    }

}
