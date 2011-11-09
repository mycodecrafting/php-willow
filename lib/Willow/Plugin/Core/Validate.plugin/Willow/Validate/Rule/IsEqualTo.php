<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsEqualTo extends Willow_Validate_Rule_Abstract
{

    /**
     * ...
     */
    protected $_comparison;

    /**
     * ...
     */
    public function __construct($comparison)
    {
        $this->_comparison = $comparison;
    }

    /**
     * Validate $value matches $comparison using unstrict comparison
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if ($value != $this->_comparison)
        {
            return $this->_throwError();
        }

        return true;
    }

}
