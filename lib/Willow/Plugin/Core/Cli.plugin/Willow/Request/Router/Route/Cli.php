<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Router_Route_Cli implements Willow_Request_Router_Route_Interface
{

    public function match(Willow_Request_Interface $request)
    {
        $params = array(
            'module'  => $request->getDefaultModule(),
            'section' => $request->getDefaultSection(),
            'action'  => $request->getDefaultAction(),
            'params'  => array(),
        );

        if (($command = $request->argv()->sanitized()->command) !== '')
        {
            $params['section'] = $command;
        }

        return $params;
    }

}