<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Validate_Rule_Filter extends Willow_Validate_Rule_Abstract
{

    /**
     * ...
     */
    public function __construct()
    {
        if (!function_exists('filter_var'))
        {
            throw new Willow_Validate_Rule_Exception(
                'This validation rule requires the PHP 5 filter extension to be installed.'
            );
        }
    }

}
