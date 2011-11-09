<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsAlphaNumeric extends Willow_Validate_Rule_Abstract
{

    /**
     * Validate that $value contains only alpha-numeric characters
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (ctype_alnum($value) === false)
        {
            return $this->_throwError();
        }

        return true;
    }

}
