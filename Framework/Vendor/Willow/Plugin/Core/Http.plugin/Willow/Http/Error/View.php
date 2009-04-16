<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Http_Error_View extends Willow_Http_View
{

    /**
     * @var Willow_Http_Error
     */
    protected $_error;

    /**
     * Sets the error
     *
     * @param Willow_Http_Error $error
     * @return void
     */
    public function setError(Willow_Http_Error $error)
    {
        $this->_error = $error;
    }


    protected function _setHeaders()
    {
        $this->_setStatus($this->_error->getCode());
    }

    public function generate()
    {
		$requestUri = $this->getRequest()->server()->sanitized()->request_uri;

		switch ($this->_error->getCode())
		{
			case 403:
				$message = sprintf(
				    '<p>You don\'t have permission to access <strong>%s</strong> on this server.</p>',
				    $requestUri
				);
				break;

			case 404:
				$message = sprintf(
				    '<p>The requested URI <strong>%s</strong> was not found on this server.</p>',
				    $requestUri
				);
				break;

			default:
				$message = $this->_error->getMessage();
				break;

		}

        echo '',
            '<html>',
            '<head>',
                '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
                '<title>Willow</title>',
            '</head>',
            '<body style="font-family: Verdana, sans-serif;">',
                '<div style="text-align: center; margin-top: 10%">',
                    '<h1 style="margin-bottom: 25px; color: #93B876;">Willow</h1>',
                    '<h2>', $this->getStatusMessage(), '</h2>',
                    '<p>', $message, '</p>',
                '</div>',
                '<div style="margin-top: 25px;">',
                    '<h3>Stack Trace:</h3>',
                    '<pre>', htmlspecialchars($this->_error->getTraceAsString()), '</pre>',
                '</div>',
            '</body>',
            '</html>';
    }

    public function getStatusMessage($code = '')
    {
        return parent::getStatusMessage($this->_error->getCode());
    }

}
