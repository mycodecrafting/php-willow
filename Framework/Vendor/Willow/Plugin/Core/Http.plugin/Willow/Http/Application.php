<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Http_Application extends Willow_Application_Abstract
{

    protected function _execute()
    {
        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowHttpApplicationStart();

        try
        {
            /**
             * Attach the view to the actions
             */
            $this->getActions()->attachView($this->getView());

            /**
             * Run the actions
             */
            $this->getActions()->run();

            /**
             * Render the view
             */
            $this->getView()->render();
        }

        /**
         * An HTTP error was thrown
         */
        catch (Willow_Http_Error $e)
        {
            $this->_executeHttpError($e);
        }

        /**
         * An HTTP redirect was thrown
         */
        catch (Willow_Http_Redirect $e)
        {
            $this->_executeHttpRedirect($e);
        }

        /**
         * Trap all others and issue a 500 Internal Server Error
         *//*
        catch (Exception $e)
        {
            $this->_executeHttpError(
                new Willow_Http_Error(500, $e->getMessage())
            );
        }
        */

        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doWillowHttpApplicationStop();
    }


    protected function _executeHttpError(Willow_Http_Error $error)
    {
        /**
         * Create HTTP error actions instance
         */
        $actions = new Willow_Http_Error_Actions($this->getRequest());

        /**
         * Create HTTP error view instance
         */
        $view = new Willow_Http_Error_View($this->getRequest());

        /**
         * Setup error
         */
        $actions->setError($error);
        $view->setError($error);

        /**
         * Attach the view to the actions
         */
        $actions->attachView($view);

        /**
         * Run the actions
         */
        $actions->run();

        /**
         * Render the view
         */
        $view->render();
    }


    protected function _executeHttpRedirect(Willow_Http_Redirect $redirect)
    {
        /**
         * Create HTTP error actions instance
         */
        $actions = new Willow_Http_Redirect_Actions($this->getRequest());

        /**
         * Create HTTP error view instance
         */
        $view = new Willow_Http_Redirect_View($this->getRequest());

        /**
         * Setup error
         */
        $actions->setRedirect($redirect);
        $view->setRedirect($redirect);

        /**
         * Attach the view to the actions
         */
        $actions->attachView($view);

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
