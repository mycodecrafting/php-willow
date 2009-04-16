<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_HasLengthOf extends Willow_Validate_Rule_Asbtract
{

    /**
     * ...
     */
    protected $_min = 0;

    /**
     * ...
     */
    protected $_max = false;

    /**
     * ...
     */
    public function __construct($min, $max = false)
    {
        $this->_min = intval($min);

        if ($max !== false)
        {
            $this->_max = intval($max);
        }
    }

    /**
     * Validate that the length of $value is not less than $min, and
     * not more than $max (if $max is given)
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        $length = Willow::utils()->string($value)->length();

        /**
         * must not be less than $min
         */
        if ($length < $this->_min)
        {
            return $this->_throwError();
        }

        /**
         * must not be more than $max, if $max is given
         */
        elseif (($this->_max !== false) && ($length > $this->_max))
        {
            return $this->_throwError();
        }

        return true;
    }

}
