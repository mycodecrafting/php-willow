<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Cli_Application extends Willow_Application_Abstract
{

    protected function _execute()
    {
        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowCliApplicationStart();

        /**
         * Create view <---> actions links
         */
        $this->getActions()->attachView($this->getView());
        $this->getView()->attachActions($this->getActions());

        /**
         * Run the actions
         */
        $this->getActions()->run();

        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowCliApplicationStop();
    }

}
