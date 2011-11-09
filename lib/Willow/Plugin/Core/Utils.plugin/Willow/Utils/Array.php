<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Array utilities
 */
class Willow_Utils_Array
{

    protected $_array = array();

    public function __construct(array $array = array())
    {
        $this->_array = $array;
    }

    /**
     * Turn an array into a string (key1=value1&key2=value2...&keyn=valuen)
     */
    public function toString($exclude = '')
    {
        if (!is_array($exclude))
        {
            $exclude = array($exclude);
        }

        $string = '';

        foreach ($this->_array as $key => $value)
        {
            if (in_array($key, $exclude) === true)
            {
                continue;
            }

            if (is_array($value === true))
            {
                foreach ($value as $key2 => $value2)
                {
                    $string .= $key . '[' . $key2 . ']' . '=' . $value2 . '&';
                }
            }
            else
            {
                $string .= $key . '=' . $value . '&';
            }
        }

        $string = substr($string, 0, -1);

        return $string;
    }

}
