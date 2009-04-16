<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_AsString extends Willow_Request_Sanitized_Default
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

}
