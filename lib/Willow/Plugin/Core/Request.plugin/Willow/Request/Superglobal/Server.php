<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Request_Superglobal_Server extends Willow_Request_Abstract
{

    public function __construct()
    {
        /**
         * Change to lower case
         */
        $properties = array_change_key_case($_SERVER, CASE_LOWER);

        /**
         * Import properties
         */
        $this->import($properties);

        /**
         * Setup a "client_ip" property
         */
        $this->_setupClientIp();

        /**
         * Attempt to setup "php_auth_user" and "php_auth_pw" support
         */
        $this->_setupPhpAuthSupport();
    }

    /**
     * Get a sanitized version of request
     */
    public function sanitized()
    {
        if (($this->_sanitized instanceof Willow_Request_Sanitized) === false)
        {
            $this->_sanitized = new Willow_Request_Sanitized($this);
            $this->_sanitized->registerTransient('default', 'Willow_Request_Sanitized_Server');
        }

        return $this->_sanitized;
    }

    protected function _setupClientIp()
    {
        /**
         * $_SERVER['HTTP_X_FORWARDED_FOR'] is set
         */
        if (isset($this->_properties['http_x_forwarded_for']))
        {
            $forwardedIps = explode(',', $this->_properties['http_x_forwarded_for']);

            $this->_properties['client_ip'] = trim(array_pop($forwardedIps));

            /**
             * Re-write "http_x_forwarded_for" as array
             */
            $this->_properties['http_x_forwarded_for'] = array();

            foreach ($forwardedIps as $ip)
            {
                $this->_properties['http_x_forwarded_for'][] = trim($ip);
            }
        }

        /**
         * $_SERVER['REMOTE_ADDR'] is set
         */
        elseif (isset($this->_properties['remote_addr']))
        {
            $this->_properties['client_ip'] = $this->_properties['remote_addr'];
        }

        /**
         * No IP address found; set to 0.0.0.0
         */
        else
        {
            $this->_properties['client_ip'] = '0.0.0.0';
        }
    }

    /**
     * @var array Known SAPIs that provide native PHP_AUTH_USER support
     */
    protected $_phpAuthSupportedSapis = array(
        'apache',
        'litespeed',
    );

    /**
     * Attempt to support PHP_AUTH_USER & PHP_AUTH_PW if they aren't supported in this SAPI
     *
     * Known SAPIs that provide native support:
     *   - apache
     *   - litespeed
     */
    protected function _setupPhpAuthSupport()
    {
        /**
         * Running on a known natively supported SAPI
         */
        if (in_array(PHP_SAPI, $this->_phpAuthSupportedSapis))
        {
            return;
        }

        /**
         * $_SERVER['PHP_AUTH_USER'] is set; assume it is supported
         */
        if (isset($this->_properties['php_auth_user']))
        {
            return;
        }

        /**
         * Attempt to emulate support by using one of:
         *   - $_SERVER['HTTP_AUTHORIZATION']
         *   - $_SERVER['AUTHORIZATION']
         *   - $_SERVER['REMOTE_USER']
         */
        $type = false;

        foreach (array('http_authorization', 'authorization', 'remote_user') as $property)
        {
            if (isset($this->_properties[$property]) && !empty($this->_properties[$property]))
            {
                list($type, $encoded) = explode(' ', $this->_properties[$property]);
                break;
            }
        }

        if ($type !== 'Basic')
        {
            return;
        }

        list($user, $pass) = explode(':', base64_decode($encoded));

        $this->import(array(
            'php_auth_user' => $user,
            'php_auth_pw'   => $pass,
        ));
    }

}
