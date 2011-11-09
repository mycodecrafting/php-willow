<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Email_Application extends Willow_Application_Abstract
{

    protected function _execute()
    {
        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowEmailApplicationStart();

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
         * Render the view
         */
        $this->getView()->render();

        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowEmailApplicationStop();
    }

}
