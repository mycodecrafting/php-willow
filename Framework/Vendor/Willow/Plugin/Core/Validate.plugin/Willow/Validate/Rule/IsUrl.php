<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsUrl extends Willow_Validate_Rule_Filter
{

    /**
     * Validate that $value is a valid url
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        $flags = FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED;

        if (filter_var($value, FILTER_VALIDATE_URL, $flags) === false)
        {
            return $this->_throwError();
        }

        return true;
    }

}
