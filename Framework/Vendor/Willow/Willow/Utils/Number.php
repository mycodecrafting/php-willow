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

    public function toFraction()
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
