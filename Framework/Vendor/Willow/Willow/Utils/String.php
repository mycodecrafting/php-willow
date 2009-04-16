<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * String utils
 */
class Willow_Utils_String
{

    /**
     * @var string The string to perform util methods on
     */
    protected $_string;

    /**
     * Constructor
     */
    public function __construct($string = null)
    {
        $this->_string = strval($string);
    }


    public function autoType($stripQuotes = false)
    {
        if (preg_match('/^(true|false|on|off|yes|no|y|n)$/i', $this->_string))
        {
            return $this->toBoolean();
        }

        if ($stripQuotes === true)
        {
            return $this->stripQuotes();
        }

        return $this->_string;
    }

    public function toCamelCase()
    {
        $string = strtolower($this->_string);
        $string = str_replace('_', '-', $string);
        return implode('', array_map('ucfirst', explode('-', $string)));
    }

    public function toBoolean()
    {
        $subject = strtolower($this->_string);
        if (preg_match('/^(true|on|yes|y)$/', $subject))
        {
            return true;
        }

        return false;
    }

    public function toFloat()
    {
        return floatval($this->_string);
    }

    public function toInt()
    {
        return intval($this->_string);
    }

    public function stripQuotes()
    {
        return preg_replace('/(^"(.+)"$)|(^\'(.+)\'$)/', '$2$4', $this->_string);
    }

    public function countWords()
    {
        // strip single quotes to avoid possive nouns from being counted as 2 words
        $string = str_replace("'", '', $this->_string);

        // strip fancy single quotes
        $string = preg_replace('/(\342\200\230|\342\200\231)/', '', $string);

        // split on whitespace
        mb_regex_encoding('UTF-8');
        $words = mb_split('\W+', $string);

        // remove empty "words"
        while (($key = array_search('', $words)) !== false)
        {
            unset($words[$key]);
        }

        // return final count
        return count($words);
    }

    public function length()
    {
        return mb_strlen($this->_string, 'UTF-8');
    }

    public function __get($property)
    {
        if (method_exists($this, $property) === true)
        {
            $this->_string = $this->$property();
            return $this;
        }
    }

}
