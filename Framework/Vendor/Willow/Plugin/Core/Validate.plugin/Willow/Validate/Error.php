<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Error
{

    /**
     * ...
     */
    protected $_field;

    /**
     * ...
     */
    protected $_error;

    /**
     * ...
     */
    public function __construct($field, Willow_Validate_Rule_Exception $e)
    {
        $this->_field = $field;
        $this->_error = $e;
    }

    /**
     * ...
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * ...
     */
    public function getMessage()
    {
        return $this->_error->getMessage();
    }

}
