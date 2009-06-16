<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * HTTP View
 */
abstract class Willow_Http_View extends Willow_View_Abstract
{

    /**
     * @var array HTTP status code => status string pairs
     */
    protected $_statuses = array(
        '200' => 'OK',
        '300' => 'Multiple Choices',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '303' => 'See Other',
        '304' => 'Not Modified',
        '305' => 'Use Proxy',
        '307' => 'Temporary Redirect',
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


    /**
     * set template
     */
    public function setTemplate($tpl, $useModule = true, $module = false)
    {
        if ($useModule === true)
        {
            if ($module === false)
            {
                $module = $this->getRequest()->getModule();
            }

            $dataPath = sprintf('App:Modules:%s:View:Templates:Sections:%s', $module, $tpl);
        }
        else
        {
            $dataPath = sprintf('App:View:Templates:Sections:%s', $tpl);
        }

        $this->getTemplate()->setTemplate(
            Willow_Loader::getRealPath($dataPath, $overridable = true, $ext = 'html')
        );
    }

    /**
     * Set template layout
     */
    public function setLayout($tpl, $useModule = true, $module = false)
    {
        if ($tpl !== false)
        {
            if ($useModule === true)
            {
                if ($module === false)
                {
                    $module = $this->getRequest()->getModule();
                }

                $dataPath = sprintf('App:Modules:%s:View:Templates:Layouts:%s', $module, $tpl);
            }
            else
            {
                $dataPath = sprintf('App:View:Templates:Layouts:%s', $tpl);
            }
        }
        else
        {
            $dataPath = false;
        }

        $this->getTemplate()->setLayout($dataPath);
    }

    /**
     * Render the view
     */
    public function render()
    {
        $this->_setDefaultHeaders();
        $this->_setHeaders();

        /**
         * Setup templates when not disabled
         */
        if ($this->isTemplateDisabled() === false)
        {
            /**
             * Set default template path if not set
             */
            if ($this->getTemplate()->getTemplate() === false)
            {
                $this->setTemplate(
                    $this->getRequest()->getSection() . $this->getRequest()->getAction()
                );
            }

            /**
             * Set default template layout path if not set
             */
            if ($this->getTemplate()->getLayout() === false)
            {
                $this->setLayout('Default');
            }
        }

        parent::render();
    }

    /**
     * Set the default HTTP headers
     */
    protected function _setDefaultHeaders()
    {
        $this->_setHeader('X-Powered-By', 'Willow Framework; PHP/' . PHP_VERSION);
        $this->_setStatus(200);
        $this->_setContentType('text/html');

        /**
         * Send 500 header if there are validation errors
         */
        if (count($this->getValidationErrors()) > 0)
        {
            $this->_setStatus(500);
        }
    }

    /**
     * ...
     */
    public function getStatusMessage($code = '')
    {
        $code = strval($code);

        if (isset($this->_statuses[$code]))
        {
            return $this->_statuses[$code];
        }

        throw new Willow_Http_View_Exception(sprintf(
            'Attempting to get an unknown HTTP status, %s', $code
        ));
    }

    /**
     * Set the HTTP status
     */
    protected function _setStatus($code)
    {
        if (is_numeric($code))
        {
            $code = strval($code);

            if (isset($this->_statuses[$code]))
            {
                header(sprintf(
                    'HTTP/1.1 %s %s', $code, $this->_statuses[$code]
                ));

                return true;
            }
        }

        if (($key = array_search($code, $this->_statuses)) !== false)
        {
            return $this->_setStatus($key);
        }

        throw new Willow_Http_View_Exception(sprintf(
            'Attempting to set an unknown HTTP status, %s', $code
        ));
    }

    /**
     * Set content type header
     */
    protected function _setContentType($type, $charset = 'utf-8')
    {
        if ($charset !== false)
        {
            $type .= '; charset=' . $charset;
        }

        $this->_setHeader('Content-Type', $type);
    }

    /**
     * Set an HTTP header
     */
    protected function _setHeader($name, $value)
    {
        header($name . ': ' . $value);
    }


    protected function _setHeaders()
    {
    }


    /**
     * setup breadcrumbs, title, templates, etc here
     */
    public function preGenerate()
    {
    }

    public function setTitle($title)
    {
        if (($page = $this->getTemplate()->getVar('page')) === false)
        {
            $page = array();
        }

        $page['title'] = $title;

		$this->getTemplate()->setVar('page', $page);
    }

    public function addBreadcrumb($title, $uri = false)
    {
        if (($breadcrumbs = $this->getTemplate()->getVar('breadcrumbs')) === false)
        {
            $breadcrumbs = array(
                'raw' => array(),
                'asHtml' => array(),
            );
        }

        $breadcrumbs['raw'][]['crumb'] = array(
            'title' => $title,
            'uri' => $uri,
        );

        $htmlCrumb = $title;

        if ($uri !== false)
        {
            $htmlCrumb = sprintf(
                '<a href="%2$s" title=" %1$s " alt="%1$s">%1$s</a>', $title, $uri
            );
        }

        $breadcrumbs['asHtml'][] = $htmlCrumb;

        $this->getTemplate()->setVar('breadcrumbs', $breadcrumbs);
    }

}
