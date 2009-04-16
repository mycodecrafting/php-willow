<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsAnEmailAddress extends Willow_Validate_Rule_Filter
{

    /**
     * Validate that $value is a valid email address (in format only)
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false)
        {
            return $this->_throwError();
        }

        return true;
    }

}
