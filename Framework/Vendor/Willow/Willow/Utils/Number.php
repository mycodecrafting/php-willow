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

}
