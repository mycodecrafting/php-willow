<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Validate implements Willow_Registerable_Interface,
                                 Willow_Registerable_Transient_Interface
{

    /**
     * @var Willow_Request_Abstract
     */
    protected $_request = null;

    public function __construct(Willow_Request_Abstract $request)
    {
        $this->_request = $request;
    }

    /**
     * @var array validation rules
     */
    protected $_rules = array();

    /**
     * Add a validation rule to the current set
     */
    public function add($field, Willow_Validate_Rule_Asbtract $rule, $message = null)
    {
        $this->_rules[] = array(
            'field' => $field,
            'rule' => $rule,
        );

        /**
         * Set error message if passed
         */
        if ($message !== null)
        {
            $rule->orError($message);
        }
    }

}
