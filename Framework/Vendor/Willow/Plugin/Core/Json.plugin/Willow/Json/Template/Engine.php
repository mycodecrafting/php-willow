<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Json_Template_Engine implements Willow_Template_Engine_Interface
{

    /**
     * @var array
     */
    protected $_vars = array();

    /**
     * Build the template and return output
     *
     * @return string
     */
    public function build()
    {
        $json = json_encode($this->getVars());

        if (Willow_Blackboard::get('config')->app->environment === 'development')
        {
//            $json = self::format($json, 2);
        }

        return $json;
    }

    /**
     * Get a set template var
     *
     * @return mixed
     */
    public function getVar($name)
    {
        if (isset($this->_vars[$name]))
        {
            return $this->_vars[$name];
        }
    }

    /**
     * Get all template vars
     *
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Set a template var to given value
     *
     * @param string $name Template var name
     * @param mixed $value Template var value
     * @return void
     */
    public function setVar($name, $value = null)
    {
        $this->_vars[$name] = $value;
    }

    /**
     * ...
     */
    public function import(array $vars)
    {
        $this->_vars = array_merge($this->_vars, $vars);
    }

    /**
     * ...
     */
    public function getTemplate()
    {
    }

    /**
     * Set the template file
     *
     * @param mixed $file The template file
     */
	public function setTemplate($file)
	{
	}

    /**
     * Check the template exists
     *
     * @return boolean
     */
    public function templateExists()
    {
        return true;
    }

    /**
     * Can the engine have layouts (i.e., do they make sense)?
     *
     * @return boolean
     */
    public function hasLayout()
    {
        return false;
    }

    /**
     * Format JSON with indentation and newlines (human readable)
     *
     */
    public static function format($json, $indentAmount = 4, $indentChar = ' ')
    {
        $result = '';
        $level = 0;
        $strlen = strlen($json);

        for ($i = 0; $i <= $strlen; ++$i)
        {
            /**
             * Grab next character
             */
            $char = substr($json, $i, 1);

            /**
             * Character is the end of an element;
             * output new line and indent next line
             */
            if (in_array($char, array('}', ']')))
            {
                $result .= NL;
                --$level;
                $result .= str_pad('', $indentAmount * $level, $indentChar);
            }

            /**
             * Add character
             */
            $result .= $char;

            /**
             * Character is beginning of an element;
             * output new line and indent next line
             */
            if (in_array($char, array('{', '[', ',')))
            {
                $result .= NL;

                if (in_array($char, array('{', '[')))
                {
                    ++$level;
                }

                $result .= str_pad('', $indentAmount * $level, $indentChar);
            }
        }

        return $result;
    }

}
