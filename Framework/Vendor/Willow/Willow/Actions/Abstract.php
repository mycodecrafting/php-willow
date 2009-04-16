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

}
