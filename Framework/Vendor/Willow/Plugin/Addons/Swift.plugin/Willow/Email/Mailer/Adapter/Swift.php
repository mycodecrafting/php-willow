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
            ->setTo($message->getTo())
            ->setBody($message->getBody());

        /**
         * Send the message
         */
        return $mailer->send($swiftMessage);
    }

    /**
     * ...
     */
    protected function _getTransport()
    {
        $config = Willow_Blackboard::get('config')->email;

        switch ($config->swift->transport)
        {
            case 'smtp':
                $transport = Swift_SmtpTransport::newInstance($config->swift->smtp->host, $config->swift->smtp->port)
                    ->setUsername($config->swift->smtp->username)
                    ->setPassword($config->swift->smtp->password);

                if ($config->swift->smtp->encryption)
                {
                    $transport->setEncryption($config->swift->smtp->encryption);
                }
                break;

            case 'sendmail':
                $transport = Swift_SendmailTransport::newInstance($config->swift->sendmail->command);
                break;

            case 'mail':
            default:
                $transport = Swift_MailTransport::newInstance();
                break;
        }

        return $transport;
    }

}
