<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Validate_Rule_Asbtract
{

    /**
     * ...
     */
    protected $_errorMessage = '';

    /**
     * ...
     */
    protected function orError($withMessage)
    {
        $this->_errorMessage = $withMessage;
    }

    /**
     * ...
     */
    protected function _throwError()
    {
        throw new Willow_Validate_Rule_Exception($this->_errorMessage);
    }

    /**
     * Validate $value according to this rule
     *
     * @param mixed $value Value to validate using this rule
     */
    abstract public function validate($value);

}
