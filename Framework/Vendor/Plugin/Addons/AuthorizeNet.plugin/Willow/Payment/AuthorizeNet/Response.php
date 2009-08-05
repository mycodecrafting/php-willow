<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Payment_AuthorizeNet_Response
{

    /**
     * ...
     */
    protected $_rawResults = array();

    /**
     * ...
     */
    protected $_results = array();

    /**
     * ...
     */
    const CODE_APPROVED = '1';
    const CODE_DECLINED = '2';
    const CODE_ERROR    = '3';
    const CODE_HELD     = '4';

    /**
     * ...
     */
    public function __construct($responseString, $delimChar = '|')
    {
        $this->_rawResults = explode($delimChar, $responseString);

        $this->_results = array(
            'responseCode' => $this->_rawResults[0],
            'responseSubcode' => $this->_rawResults[1],
            'reasonCode' => $this->_rawResults[2],
            'reasonText' => $this->_rawResults[3],
            'authorizationCode' => $this->_rawResults[4],
            'avsResponse' => $this->_rawResults[5],
        );
    }

    /**
     * ...
     */
    public function isApproved()
    {
        return ($this->_results['responseCode'] === self::CODE_APPROVED);
    }

    /**
     * ...
     */
    public function isDeclined()
    {
        return ($this->_results['responseCode'] === self::CODE_DECLINED);
    }

    /**
     * ...
     */
    public function isError()
    {
        return ($this->_results['responseCode'] === self::CODE_ERROR);
    }

    /**
     * ...
     */
    public function getReasonText()
    {
        return $this->_results['reasonText'];
    }

}
