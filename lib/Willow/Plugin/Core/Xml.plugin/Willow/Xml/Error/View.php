<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Xml_Error_View extends Willow_Http_Error_View
{

    protected function _setHeaders()
    {
        parent::_setHeaders();
        $this->_setContentType('text/xml');
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

        $error = new Willow_Data_Node('error');

        $error->createChild('code')->setValue($this->_error->getCode());
        $error->createChild('status')->setValue($this->getStatusMessage());
        $error->createChild('message')->setValue($message);
        $error->createChild('requestUri')->setValue($requestUri);

        if (Willow_Blackboard::get('config')->app->environment === 'development')
        {
            $stackTrace = $error->createChild('stackTrace');

            foreach (explode("\n", $this->_error->getTraceAsString()) as $frame)
            {
                $stackTrace->createChild('frame')->setValue($frame);
            }
        }

        echo $error->asXml();
    }

}
