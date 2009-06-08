<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * JSON view
 */
abstract class Willow_Json_View extends Willow_Http_View
{

    /**
     * Render the view
     */
    public function render()
    {
        $this->_setDefaultHeaders();
        $this->_setHeaders();

        $this->setLayout(false);

        Willow_View_Abstract::render();
    }

    /**
     * Set the default HTTP headers
     */
    protected function _setDefaultHeaders()
    {
        parent::_setDefaultHeaders();
        $this->_setContentType('application/json');

        /**
         * Send 500 header if there are validation errors
         */
        if (count($this->getValidationErrors()) > 0)
        {
            $this->_setStatus(500);
        }
    }
/*
    public function setValidationError($field, $message, $userCanBypass = false)
    {
        if (($errors = $this->getTemplate()->getVar('errors')) === false)
        {
            $errors = array();
        }

        /**
         * Push error message to error stack
         *//*
        $errors[] = array(
            'field' => $field,
            'message' => $message,
            'userCanBypass' => $userCanBypass,
        );

        /**
         * Update error stack in template
         *//*
        $this->getTemplate()->setVar('errors', $errors);
    }
*/
    public function preGenerate()
    {
    }

}
