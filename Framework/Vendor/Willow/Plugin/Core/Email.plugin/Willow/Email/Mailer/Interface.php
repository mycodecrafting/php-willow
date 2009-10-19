<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
interface Willow_Email_Mailer_Interface
{

    /**
     * Send the email message
     *
     * @param Willow_Email_Message $message Email message to send
     */
    public function send(Willow_Email_Message $message);

}
