<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Session_Security_Fingerprint extends Willow_Session_Security_Abstract
{

    /**
     * ...
     */
    public function takeMeasures()
    {
        if (!isset($this->_session->fingerprint))
        {
            $this->_session->fingerprint = $this->generateFingerprint();
        }

        /**
         * Fingerprint mismatch
         */
        elseif ($this->_session->fingerprint !== $this->generateFingerprint())
        {
            Willow_Session::destroy();
/*
            throw new Willow_Session_Security_Exception(
                ''
            );
*/
        }
    }

    /**
     * ...
     */
    public function resetFingerprint()
    {
        $this->_session->fingerprint = $this->generateFingerprint();
    }

    /**
     * ...
     */
    public function generateFingerprint()
    {
        if (Willow_Session::isStarted() === false)
        {
            return;
        }

        $user_agent = getenv('HTTP_USER_AGENT');
        $token = Willow_Session::getConfig()->security->fingerprintToken;
        $fingerprint = '__WF_FINGERPRINT-' . $user_agent . '-' . $token . '-' . Willow_Session::getId();
        return sha1($fingerprint);
    }

}
