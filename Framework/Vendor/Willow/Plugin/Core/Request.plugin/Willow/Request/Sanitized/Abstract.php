<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Willow_Request_Sanitized_Abstract
{

    /**
     * @var Willow_Request_Abstract
     */
    protected $_request;

    /**
     * Constructor
     *
     * @param Willow_Request_Abstract $request
     */
    public function __construct(Willow_Request_Abstract $request)
    {
        $this->_request = $request;
    }

    /**
     * Sanitize value
     *
     * @param mixed $value
     */
    public function sanitize($value)
    {
        /**
         * Value is array; process each value of array
         */
        if (is_array($value))
        {
            foreach ($value as $key => $innerValue)
            {
                $value[$key] = $this->sanitize($innerValue);
            }

            return $value;
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
     * @return mixed Sanitized value
     */
    abstract protected function _sanitize($value);

    public function __get($property)
    {
        if (isset($this->_request->$property))
        {
            return $this->sanitize($this->_request->$property);
        }
    }

}
