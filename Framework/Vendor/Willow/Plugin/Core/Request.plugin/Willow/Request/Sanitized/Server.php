<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_Server extends Willow_Request_Sanitized_Default
{

    /**
     * Meat and guts of sanitization
     *
     * @param mixed $value
     * @return string Sanitized value
     */
    protected function _sanitize($value)
    {
        return parent::_sanitize(strval($value));
    }

    /**
     * Automatically sanitize certain properties with certain filters
     */
    public function __get($property)
    {
        switch ($property)
        {
        	case 'client_ip':
        	case 'remote_addr':
        	case 'server_addr':
        	case 'http_x_forwarded_for':
        	    $sanitizer = new Willow_Request_Sanitized_AsIpAddress($this->_request);
        	    return $sanitizer->$property;
                break;

            default:
                if (isset($this->_request->$property))
                {
                    return $this->sanitize($this->_request->$property);
                }
                break;
        }
    }

}
