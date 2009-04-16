<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Register default request handlers
 */
Willow_Request::register('get', 'Willow_Request_Superglobal_Get');
Willow_Request::register('post', 'Willow_Request_Superglobal_Post');
Willow_Request::register('cookie', 'Willow_Request_Superglobal_Cookie');
Willow_Request::register('files', 'Willow_Request_Superglobal_Files');
Willow_Request::register('server', 'Willow_Request_Superglobal_Server');
Willow_Request::register('env', 'Willow_Request_Superglobal_Env');
Willow_Request::register('argv', 'Willow_Request_Superglobal_Argv');

/**
 * Register default sanitizers
 */
Willow_Request_Sanitized::register('asInt', 'Willow_Request_Sanitized_AsInt');
Willow_Request_Sanitized::register('asFloat', 'Willow_Request_Sanitized_AsFloat');
Willow_Request_Sanitized::register('asNumeric', 'Willow_Request_Sanitized_AsNumeric');
Willow_Request_Sanitized::register('asBoolean', 'Willow_Request_Sanitized_AsBoolean');
Willow_Request_Sanitized::register('asString', 'Willow_Request_Sanitized_AsString');
Willow_Request_Sanitized::register('asIpAddress', 'Willow_Request_Sanitized_AsIpAddress');
Willow_Request_Sanitized::register('default', 'Willow_Request_Sanitized_Default');
