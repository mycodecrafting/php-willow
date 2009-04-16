<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


try
{
    /**
     * Load Http Application Bootstrap
     */
    require dirname(__FILE__) . '/../App/Bootstrap/Http.php';

    /**
     * Exceute application
     */
    Willow_Application::getInstance()->dispatch();
}

/**
 * Caught Exception; display generic message
 */
catch (Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');
    echo '',
        '<html>',
        '<head>',
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">',
            '<title>Willow Framework</title>',
        '</head>',
        '<body style="font-family: Verdana, sans-serif;">',
            '<div style="text-align: center; margin-top: 10%">',
                '<h1 style="margin-bottom: 25px; color: #93B876;">Willow</h1>',
                '<h2>Internal Server Error</h2>',
                '<p>', $e->getMessage(), '</p>',
            '</div>',
            '<div style="margin-top: 25px;">',
                '<h3>Stack Trace:</h3>',
                '<pre>', htmlspecialchars($e->getTraceAsString()), '</pre>',
            '</div>',
            '<div style="margin-top: 25px;">',
                '<h3>Code Trace:</h3>',
                Willow_Debug::getCodeTrace($e),
            '</div>',
        '</body>',
        '</html>';
}
