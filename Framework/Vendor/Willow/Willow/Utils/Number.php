<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Number utils
 */
class Willow_Utils_Number
{

    protected $_number;

    public function __construct($number = 0)
    {
        $this->_number = floatval($number);
    }

    /**
     * Calculate the percentage off a given price
     */
    public function percentageOff($given, $precision = 0)
    {
        $given = floatval($given);

        $percentage = 0;

        if ($given > 0)
        {
            $percentage = (100 * ($given - $this->_number) / $given);
        }

        return round($percentage, $precision);
    }

    /**
     * square a number
     */
    public function squared()
    {
        return floatval(pow($this->_number, 2));
    }

    /**
     * Get the square root of a number
     */
    public function squareRoot()
    {
        return sqrt($this->_number);
    }

    /**
     * Based on he algorithm presented in http://homepage.smc.edu/kennedy_john/DEC2FRAC.PDF
     */
    public function toFraction($precision = 5.0E-7)
    {
        $decimalSign = $this->_number < 0 ? -1 : 1;

        $decimal = abs($this->_number);

        if ($decimal == ($integer = floor($decimal)))
        {
            return $decimal * $decimalSign;
        }

        $decimal = $decimal - $integer;

        /**
         * Cannot handle anything less than 1/9999999999999999999
         */
        if ($decimal < 1.0E-19)
        {
            return $decimalSign . '/9999999999999999999';
        }

        /**
         * Cannot handle anything greater than 9,999,999,999,999,999,999/1
         */
        if ($decimal > 1.0E+19)
        {
            return '9999999999999999999';
        }

        $z = $decimal;
        $prevDenominator = 0;
        $denominator = 1;

        do
        {
            $z = 1 / ($z - intval($z));
            $scratch = $denominator;
            $denominator = $denominator * intval($z) + $prevDenominator;
            $prevDenominator = $scratch;
            $numerator = intval($decimal * $denominator + 0.5);
        }
        while ( (abs($decimal - ($numerator / $denominator)) >= $precision) && ($z != intval($z)) );

        $numerator = $numerator * $decimalSign;

        return ($integer ? $integer . ' ' : '') . $numerator . '/' . $denominator;
    }

    public function toFractionOld()
    {
        if (floor($this->_number) == $this->_number)
        {
            return $this->_number;
        }

        $integer = floor($this->_number);
        $decimal = $this->_number - $integer;
        $numerator = 1;
        $denominator = (10e15 - 1) / ($decimal * 10e15);
        $remainder = $denominator - floor($denominator);
        $factor = $remainder < 1e-10 ? 1 : (10e15 - 1) / ($remainder * 10e15);

        $numerator *= $factor;
        $denominator *= $factor;

        $factorRemainder = $factor - floor($factor);

        if ($factorRemainder > 1e-10)
        {
            $factor = (10e15 - 1) / ($factorRemainder * 10e15);
            $numerator *= $factor;
            $denominator *= $factor;
        }

        return ($integer ? $integer . ' ' : '') . round($numerator) . '/' . round($denominator);
    }

}
