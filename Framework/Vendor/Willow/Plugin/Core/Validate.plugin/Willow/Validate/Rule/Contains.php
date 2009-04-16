<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_Contains extends Willow_Validate_Rule_Asbtract
{

    /**
     * ...
     */
    protected $_needle;

    /**
     * ...
     */
    public function __construct($needle)
    {
        $this->_needle = $needle;
    }

    /**
     * Validate that $value contains $needle
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (strpos($value, $this->_needle) === false)
        {
            return $this->_throwError();
        }

        return true;
    }

}
