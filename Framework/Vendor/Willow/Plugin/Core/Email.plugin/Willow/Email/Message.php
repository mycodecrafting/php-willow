<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Email_Message
{

    /**
     * ...
     */
    protected $_message = array(
        'to'        => array(),
        'cc'        => array(),
        'bcc'       => array(),
        'from'      => array(),
        'sender'    => array(),
        'replyTo'   => null,
        'subject'   => null,
        'body'      => null,
        'altBody'   => null,
        'priority'  => null,
    );

    /**
     * ...
     */
    public function addTo($address, $name = null)
    {
        $this->_message['to'][$address] = $name;
        return $this;
    }

    /**
     * ...
     */
    public function getTo()
    {
        return $this->_message['to'];
    }

    /**
     * ...
     */
    public function addCc($address, $name = null)
    {
        $this->_message['cc'][$address] = $name;
        return $this;
    }

    /**
     * ...
     */
    public function getCc()
    {
        return $this->_message['cc'];
    }

    /**
     * ...
     */
    public function addBcc($address, $name = null)
    {
        $this->_message['bcc'][$address] = $name;
        return $this;
    }

    /**
     * ...
     */
    public function addRecipients(array $recipients)
    {
        foreach ($recipients as $address => $name)
        {
            if (is_numeric($address))
            {
                $address = $name;
                $name = null;
            }

            $this->addTo($address, $name);
        }
    }

    /**
     * Returns array of to, cc, and bcc recipients
     */
    public function getRecipients()
    {
        return array_merge($this->_message['to'], $this->_message['cc'], $this->_message['bcc']);
    }

    /**
     * ...
     */
    public function resetRecipients()
    {
        $this->_message['to'] = array();
        $this->_message['cc'] = array();
        $this->_message['bcc'] = array();
        return $this;
    }

    /**
     * ...
     */
    public function setBody($body)
    {
        $this->_message['body'] = $body;
        return $this;
    }

    /**
     * ...
     */
    public function getBody()
    {
        return $this->_message['body'];
    }

    /**
     * ...
     */
    public function setAltBody($text)
    {
        $this->_message['altBody'] = $text;
        return $this;
    }

    /**
     * ...
     */
    public function getAltBody()
    {
        return $this->_message['altBody'];
    }

    /**
     * ...
     */
    public function setReplyTo($address, $name = null)
    {
        $this->_message['replyTo'] = array($address => $name);
        return $this;
    }

    /**
     * ...
     */
    public function getReplyTo()
    {
        return $this->_message['replyTo'];
    }

    /**
     * ...
     */
    public function setSender($address, $name = null)
    {
        $this->_message['sender'] = array($address => $name);
        return $this;
    }

    /**
     * ...
     */
    public function getSender()
    {
        return $this->_message['sender'];
    }

    /**
     * ...
     */
    public function setSubject($subject)
    {
        $this->_message['subject'] = $subject;
        return $this;
    }

    /**
     * ...
     */
    public function getSubject()
    {
        return $this->_message['subject'];
    }

    /**
     * ...
     */
    public function setPriority($priority)
    {
        $this->_message['priority'] = $priority;
        return $this;
    }

    /**
     * ...
     */
    public function getPriority()
    {
        return $this->_message['priority'];
    }

}
