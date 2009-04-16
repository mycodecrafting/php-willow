<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsNumeric extends Willow_Validate_Rule_Asbtract
{

    /**
     * Validate that $value is numeric
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (is_numeric($value) === false)
        {
            return $this->_throwError();
        }

        return true;
    }

}
