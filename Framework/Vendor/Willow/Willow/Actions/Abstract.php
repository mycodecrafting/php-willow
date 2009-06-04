<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Base controller class
 */
abstract class Willow_Actions_Abstract implements Willow_Actions_Interface
{

    /**
     * @var Willow_Request
     */
    protected $_request;

    /**
     * ...
     */
    protected $_plexus;

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
     * Get request object
     *
     * @return Willow_Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * ...
     */
    public function getPlexus()
    {
        return $this->_plexus;
    }

    /**
     * ...
     */
    public function run()
    {
        if ($this->authenticate() !== true)
        {
            return;
        }

        /**
         * Calls hooks xxxWillowActionsDoAction
         */
        $this->getPlexus()->doWillowActionsDoAction();

        /**
         * Run actions
         */
        $this->getPlexus()->doDoAction($this);
    }

    /**
     * ...
     */
    public function authenticate()
    {
        return true;
    }

    /**
     * @var Willow_View_Interface
     */
    protected $_view;

    /**
     * ...
     */
    public function attachView(Willow_View_Interface $view)
    {
        $this->_view = $view;
    }

    /**
     * ...
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * Forward request to another action, section, or module
     */
    protected function _forward(array $forwardTo)
    {
        /**
         * Change in action
         */
        if (array_key_exists('action', $forwardTo) === true)
        {
            $this->getRequest()->setAction($forwardTo['action']);
        }

        /**
         * Change in section
         */
        if (array_key_exists('section', $forwardTo) === true)
        {
            $this->getRequest()->setSection($forwardTo['section']);
        }

        /**
         * Change in module
         */
        if (array_key_exists('module', $forwardTo) === true)
        {
            $this->getRequest()->setModule($forwardTo['module']);
        }

        /**
         * Create an instance of actions factory
         */
        $factory = new Willow_Actions_Factory($this->getRequest());
        $class = $factory->getClass();
        unset($factory);

        /**
         * Create forwarded to actions class instance
         */
        $actions = new $class($this->getRequest());

        /**
         * Attach the view
         */
        $actions->attachView($this->getView());

        /**
         * Perform the action
         */
        return $actions->doAction();
    }

}
