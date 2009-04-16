<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Route_Default implements Willow_Request_Router_Route_Interface
{

    public function match(Willow_Request_Interface $request)
    {
        $params = array(
            'module'  => 'default',
            'section' => 'index',
            'action'  => 'index',
            'params'  => array(),
        );

        // :module/:section/:action
        if (($module = $request->segment(1)) === false)
        {
            return $params;
        }

        $params['module'] = $module;

        if (($section = $request->segment(2)) === false)
        {
            return $params;
        }

        $params['section'] = $section;

        if (($action = $request->segment(3)) === false)
        {
            return $params;
        }

        $params['action'] = $action;

        /**
         * path is too long to match :module/:section/:action
         */
        if ($request->segment(4) !== false)
        {
            return false;
        }

        return $params;
    }

}