<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Session_Security_Fixation extends Willow_Session_Security_Abstract
{

    public function takeMeasures()
    {
        if ($this->_session->getVar('initiated') === null)
        {
            $this->_session->setVar('initiated', true, $regenerate = true);
        }
    }

}
