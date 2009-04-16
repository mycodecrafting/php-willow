<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Http Redirect
 */
class Willow_Http_Redirect extends Exception
{

    /**
     * @var array HTTP redirect statuses
     */
    protected $_statuses = array(
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '307' => 'Temporary Redirect',
    );

    /**
     * Constructor
     */
    public function __construct($path, $code = 302)
    {
        if (is_numeric($code))
        {
            $code = strval($code);
        }

        if (!isset($this->_statuses[$code]))
        {
            if (($key = array_search($code, $this->_statuses)) !== false)
            {
                $code = $key;
            }
        }

        parent::__construct($path, intval($code));
    }

    public function getUri()
    {
        return $this->getMessage();
    }

}
