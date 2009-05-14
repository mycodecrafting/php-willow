<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Json_Error_View extends Willow_Http_Error_View
{

    protected function _setHeaders()
    {
        parent::_setHeaders();
        $this->setTemplateDisabled(false);
        $this->_setContentType('text/json');
    }

    public function generate()
    {
		$requestUri = $this->getRequest()->server()->sanitized()->request_uri;

		switch ($this->_error->getCode())
		{
			case 403:
				$message = sprintf(
				    'You don\'t have permission to access %s on this server.',
				    $requestUri
				);
				break;

			case 404:
				$message = sprintf(
				    'The requested URI %s was not found on this server.',
				    $requestUri
				);
				break;

			default:
				$message = $this->_error->getMessage();
				break;

		}

        $error = array(
            'code' => $this->_error->getCode(),
            'status' => $this->getStatusMessage(),
            'message' => $message,
            'requestUri' => $requestUri,
            'stackTrace' => $this->_error->getTrace(),
        );

        $this->getTemplate()->setVar('error', $error);
    }

}
