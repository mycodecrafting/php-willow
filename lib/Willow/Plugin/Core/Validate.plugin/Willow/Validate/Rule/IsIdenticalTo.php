<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsIdenticalTo extends Willow_Validate_Rule_IsEqualTo
{

    /**
     * Validate $value matches $comparison using strict comparison
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if ($value !== $this->_comparison)
        {
            return $this->_throwError();
        }

        return true;
    }

}
