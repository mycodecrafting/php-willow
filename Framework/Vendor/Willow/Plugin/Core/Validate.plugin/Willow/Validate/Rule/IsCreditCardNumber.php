<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsCreditCardNumber extends Willow_Validate_Rule_Abstract
{

    /**
     * Validate that $value is a valid credit card number using MOD10
     *
     * @param mixed $value Value to validate using this rule
     */
    public function validate($value)
    {
        /**
         * Check begins from right of number
         */
        $value = strrev($value);

        /**
         * Step 1: Double the value of alternate digits of the number
         */
        $digits = '';

        foreach (str_split($value) as $i => $digit)
        {
            $digits .= (($i % 2) ? $digit * 2 : $digit);
        }

        /**
         * Step 2: Add the individual digits comprising the products obtained in Step 1
         *         to each of the unaffected digits in the original number
         */
        $sum = 0;

        foreach (str_split($digits) as $digit)
        {
            $sum += $digit;
        }

        /**
         * Step 3: The total obtained in step 2 must be a number ending in zero (30, 40, 50, etc...)
         *         for the number to be validated
         */
        if (($sum % 10) !== 0)
        {
            return $this->_throwError();
        }

        return true;
    }

}
