<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
abstract class Willow_Search_Scoring_Abstract
{

    /**
     * ...
     */
    protected $_keywords;

    /**
     * ...
     */
    public function __construct($keywords)
    {
        $this->_keywords = $keywords;
    }

    /**
     * ...
     */
    abstract public function score($string);

    /**
     * ...
     */
    abstract public function sort($a, $b);

}
