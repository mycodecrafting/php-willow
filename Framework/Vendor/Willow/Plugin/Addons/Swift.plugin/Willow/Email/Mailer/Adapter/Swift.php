<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Email_Mailer_Adapter_Swift implements Willow_Email_Mailer_Interface
{

    /**
     * Send the email message
     *
     * @param Willow_Email_Message $message Email message to send
     */
    public function send(Willow_Email_Message $message)
    {
        /**
         * Create mailer
         */
        $mailer = Swift_Mailer::newInstance($this->_getTransport());

        /**
         * Create message
         */
        $swiftMessage = Swift_Message::newInstance($message->getSubject())
            ->setFrom($message->getSender())
            ->setTo($message->getTo());

        $body = $message->getBody();

        /**
         * Handle embeded files
         */
        foreach ($message->getEmbeded() as $id => $file)
        {
            $body = str_replace($id, $swiftMessage->embed(Swift_EmbeddedFile::fromPath($file)), $body);
        }

        /**
         * Do we have an alt body?
         */
        if ($message->getAltBody() !== null)
        {
            $swiftMessage->setBody($body, 'text/html')
                         ->addPart($message->getAltBody(), 'text/plain');
        }
        else
        {
            $swiftMessage->setBody($body);
        }

        /**
         * Send the message
         */
        return $mailer->send($swiftMessage);
    }

    /**
     * ...
     */
    protected $_transport = null;

    /**
     * Specify a specific transport to use
     */
    public function useTransport($transport)
    {
        $this->_transport = $transport;
        return $this;
    }

    /**
     * ...
     */
    public function resetTransport()
    {
        $this->_transport = null;
        return $this;
    }

    /**
     * ...
     */
    protected function _getTransport()
    {
        $config = Willow_Blackboard::get('config')->email;

        if ($this->_transport === null)
        {
            $this->_transport = $config->swift->transport;
        }

        switch ($this->_transport)
        {
            case Willow_Email_Transport::SMTP:
                $transport = Swift_SmtpTransport::newInstance($config->swift->smtp->host, $config->swift->smtp->port)
                    ->setUsername($config->swift->smtp->username)
                    ->setPassword($config->swift->smtp->password);

                if ($config->swift->smtp->encryption)
                {
                    $transport->setEncryption($config->swift->smtp->encryption);
                }
                break;

            case Willow_Email_Transport::SENDMAIL:
                $transport = Swift_SendmailTransport::newInstance($config->swift->sendmail->command);
                break;

            case Willow_Email_Transport::MAIL:
            default:
                $transport = Swift_MailTransport::newInstance();
                break;
        }

        return $transport;
    }

}
