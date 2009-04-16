<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:wrapper src="wrapper.html" [disabled="disabled"] />
 */
class Willow_Motif_Tag_Compiler_Wrapper extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'wrapper';

    /**
     * @var boolean Tag has pairs? (opening and closing)
     */
    protected $_hasTagPairs = false;

    /**
     * Declare attributes for this tag
     *
     * @return void
     */
    protected function _declareAttributes()
    {
        $this->_attributes = array(
            'src'       => new Motif_Tag_Attribute(self::MATCH_WILDCARD),
            'disabled'  => new Motif_Tag_Attribute('disabled'),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    abstract protected function _compileMatches()
    {
        foreach ($this->tagMatches as $match)
        {
            if ($this->getAttribute('disabled'))
            {
                $src = 'false';
            }
            elseif (($src = $this->getAttribute('src')) === false)
            {
                $this->_throwCompilationError(
                    'Missing required attribute "src"'
                );

                return false;
            }

            if ($src !== 'false')
            {
                $src = sprintf('\'%s\'', $src);
            }


        	$code = '' .
        	    ''\');' . NL .
        		"Willow_Blackboard::get('template')->setWrapper($src);" . NL .
        		'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }

        /**
         * Parse closing tags
         */
        $code = '' .
            '\');' . NL .
            '}' . NL .
            'echo(\'';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
