<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsNotEmpty extends Willow_Validate_Rule_Abstract
{

    /**
     * Validate that $value is not empty
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (trim($value) === '')
        {
            return $this->_throwError();
        }

        return true;
    }

}
