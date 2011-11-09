<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate_Rule_IsTrue extends Willow_Validate_Rule_IsIdenticalTo
{

    /**
     * ...
     */
    public function __construct()
    {
        parent::__construct(true);
    }

}
