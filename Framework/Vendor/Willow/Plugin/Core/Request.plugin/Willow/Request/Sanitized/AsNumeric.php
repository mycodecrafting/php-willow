<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_AsNumeric extends Willow_Request_Sanitized_Abstract
{

    /**
     * Meat and guts of sanitization
     *
     * @param mixed $value
     * @return float Sanitized value
     */
    protected function _sanitize($value)
    {
        return preg_replace('/\D+/', '', $value);
    }

}
