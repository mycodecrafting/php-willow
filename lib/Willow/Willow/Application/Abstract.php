<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */



/*

- get or generate actions class from path
- initiate actions
- run actions
- get or generate view class from path
- initiate view
- build view (as object)
- initiate response
- response renders view object (according to protocol)
*/

abstract class Willow_Application_Abstract
{

    /**
     * @var Protocol for request
     */
    protected $_protocol = 'http';

    /**
     * @var Willow_Request
     */
    protected $_request;

    /**
     * @var Plexus
     */
    protected $_corePlexus;

    /**
     * @var Willow_Actions_Abstract
     */
    protected $_actions;

    /**
     * @var Willow_View_Abstract
     */
    protected $_view;

    /**
     * Constructor
     *
     */
    public function __construct(Willow_Request_Interface $request)
    {
        $this->_request = $request;
//        $this->_request->setProtocol($this->_protocol);

        /**
         * @todo
         */
        $this->_corePlexus = new Willow_Plexus();
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
     * Get the plexus object
     *
     * @return Willow_Plexus
     */
    public function getPlexus()
    {
        return $this->_corePlexus;
    }

    /**
     * Get the core plexus object
     *
     * @return Plexus
     */
    public function getCorePlexus()
    {
        return $this->_corePlexus;
    }

    /**
     * Get the actions for the request
     *
     * @return Willow_Actions_Abstract
     */
    public function getActions()
    {
        if (($this->_actions instanceof Willow_Actions_Interface) === false)
        {
            $factory = new Willow_Actions_Factory($this->getRequest());
            $class = $factory->getClass();
            unset($factory);

            $this->_actions = new $class($this->getRequest());
        }

        return $this->_actions;
    }

    /**
     * Get the view for the request
     *
     * @return Willow_View_Abstract
     */
    public function getView()
    {
        if (($this->_view instanceof Willow_View_Interface) === false)
        {
            $factory = new Willow_View_Factory($this->getRequest());
            $class = $factory->getClass();
            unset($factory);

            $this->_view = new $class($this->getRequest());
        }

        return $this->_view;
    }


    public function execute()
    {
        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doStart();

        /**
         * Main execution
         */
        $this->_execute();

        /**
         * Run core plexus hooks
         */
        $this->getCorePlexus()->doStop();
    }

	abstract protected function _execute();

}
