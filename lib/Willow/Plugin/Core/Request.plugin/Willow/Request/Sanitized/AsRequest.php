<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_AsRequest extends Willow_Request_Sanitized_Abstract
{

    /**
     * Sanitize value
     *
     * @param mixed $value
     */
    public function sanitize($value)
    {
        if (!is_array($value))
        {
            throw new Willow_Request_Sanitized_Exception(
                'Can only process arrays with Willow_Request_Sanitized_AsRequest'
            );
        }

        /**
         * process & return value
         */
        return $this->_sanitize($value);
    }

    /**
     * Meat and guts of sanitization
     *
     * @param mixed $value
     * @return float Sanitized value
     */
    protected function _sanitize($values)
    {
        $return = array();

        foreach ($values as $value)
        {
            $return[] = new Willow_Request_Array($value);
        }

        return $return;
    }

}
