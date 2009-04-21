<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_Matches extends Willow_Validate_Rule_Abstract
{

    /**
     * ...
     */
    protected $_pattern;

    /**
     * ...
     */
    public function __construct($pattern)
    {
        $this->_pattern = $pattern;
    }

    /**
     * Validate $value matches $pattern using PCRE regular expressions
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        if (preg_match($this->_pattern, $value) !== 1)
        {
            return $this->_throwError();
        }

        return true;
    }

}
