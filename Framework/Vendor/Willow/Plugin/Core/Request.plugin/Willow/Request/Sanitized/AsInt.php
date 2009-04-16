<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_AsInt extends Willow_Request_Sanitized_Abstract
{

    /**
     * @var integer Base for integer conversion
     */
    protected $_base = 10;

    /**
     * Set base for integer conversion
     *
     * @param integer $base
     * @return self
     */
    public function setBase($base = 10)
    {
        $this->_base = intval($base);
        return $this;
    }

    /**
     * Meat and guts of sanitization
     *
     * @param mixed $value
     * @return integer Sanitized value
     */
    protected function _sanitize($value)
    {
        return intval(preg_replace('/[\$,]/', '', $value), $this->_base);
    }

}
