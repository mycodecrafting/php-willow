<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:wrapper src="wrapper.html" [disabled="disabled"] />
 */
class Willow_Motif_Tag_Compiler_Utils_Number extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'utils:number';

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
    		'var'       => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'method'    => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $varCode = $this->_parseVarName($this->getAttribute('var'));
            $method = $this->getAttribute('method');

        	$code = '' .
        	    '\');' . NL .
        		"echo Willow_Utils::number($varCode)->$method();" . NL .
        		'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
