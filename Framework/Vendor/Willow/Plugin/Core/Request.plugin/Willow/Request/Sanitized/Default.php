<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Request_Sanitized_Default extends Willow_Request_Sanitized_Abstract
{

    /**
     * @var boolean
     */
    protected $_htmlSpecialChars = true;

    /**
     * @var boolean
     */
    protected $_preventXss = true;

    /**
     * Set whether or not to use htmlspecialchars()
     *
     * @param boolean $flag
     * @return self
     */
    public function setHtmlSpecialChars($flag = true)
    {
        $this->_htmlSpecialChars = (bool)$flag;
    }

    /**
     * Set whether or not to prevent XSS attacks
     *
     * @param boolean $flag
     * @return self
     */
    public function setPreventXss($flag = true)
    {
        $this->_preventXss = (bool)$flag;
    }

    /**
     * Meat and guts of sanitization
     *
     * @param mixed $value
     * @return mixed Sanitized value
     */
    protected function _sanitize($value)
    {
        /**
         * Do HTML Special Chars
         */
        if ($this->_htmlSpecialChars === true)
        {
            $value = $this->_htmlSpecialChars($value);
        }

        /**
         * Prevent XSS
         */
        if ($this->_preventXss === true)
        {
            $value = $this->_preventXss($value);
        }

        return $value;
    }

    protected function _htmlSpecialChars($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function _preventXss($value)
    {
        return preg_replace_callback(
            '/&amp;#((?:[0-9+])|(?:x(?:[0-9A-F]+)));?/i', array($this, '_preventXssCallback'), $value
        );
    }

    protected function _preventXssCallback($matches)
    {
        if (!is_numeric(substr($matches[1], 0, 1)))
        {
            $value = '0' . $matches[1] + 0;
        }
        else
        {
            $value = intval($matches[1]);
        }

        if ($value > 255)
        {
            return '&#' . $matches[1] . ';';
        }

        if (($value >= 48 && $value <= 57) ||
            ($value >= 65 && $value <= 90) ||
            ($value >= 97 && $value <= 122)
        )
        {
            return chr($value);
        }

        return $matches[0];
    }

}
