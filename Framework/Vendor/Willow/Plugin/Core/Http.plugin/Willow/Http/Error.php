<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Http Error
 */
class Willow_Http_Error extends Exception
{

	protected $_statuses = array(
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'407' => 'Proxy Authentication Required',
		'408' => 'Request Timeout',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'502' => 'Bad Gateway',
		'503' => 'Service Unavailable',
		'504' => 'Gateway Timeout',
		'505' => 'HTTP Version Not Supported',
	);

    public function __construct($code, $message = '')
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

        if (($message === '') && isset($this->_statuses[$code]))
        {
            $message = $this->_statuses[$code];
        }

		parent::__construct($message, intval($code));
    }

}
