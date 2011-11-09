<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsInRange extends Willow_Validate_Rule_Abstract
{

    /**
     * ...
     */
    protected $_lowerBound;

    /**
     * ...
     */
    protected $_upperBound;

    /**
     * ...
     */
    public function __construct($lower, $upper)
    {
        $this->_lowerBound = intval($lower);
        $this->_upperBound = intval($upper);
    }

    /**
     * Validate that $value is not less than $lower, and not greater than $upper
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        /**
         * must not be out of boundary (less than $lower or greater than $upper)
         */
        if (($value < $this->_lowerBound) || ($value > $this->_upperBound))
        {
            return $this->_throwError();
        }

        return true;
    }

}
