<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Xml_Application extends Willow_Http_Application
{

    protected function _execute()
    {
        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowXmlApplicationStart();

        try
        {
            parent::_execute();
        }

        /**
         * Trap all others and issue a 500 Internal Server Error
         */
        catch (Exception $e)
        {
            $this->_executeHttpError(
                new Willow_Http_Error(500, $e->getMessage())
            );
        }

        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowXmlApplicationStop();
    }

    protected function _executeHttpError(Willow_Http_Error $error)
    {
        /**
         * Create HTTP error actions instance
         */
        $actions = new Willow_Xml_Error_Actions($this->getRequest());

        /**
         * Create HTTP error view instance
         */
        $view = new Willow_Xml_Error_View($this->getRequest());

        /**
         * Setup error
         */
        $actions->setError($error);
        $view->setError($error);

        /**
         * Create view <---> actions links
         */
        $actions->attachView($view);
        $view->attachActions($actions);

        /**
         * Run the actions
         */
        $actions->run();

        /**
         * Render the view
         */
        $view->render();
    }

}
