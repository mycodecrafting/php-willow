<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Register email request handlers
 */
Willow_Request::register('email', 'Willow_Request_Email');

Willow_Request::registerProtocols('email');

/**
 * Register the engine for use with Email protocol requests
 */
Willow_Template::register('email', 'Willow_Template_Adapter_Motif');
