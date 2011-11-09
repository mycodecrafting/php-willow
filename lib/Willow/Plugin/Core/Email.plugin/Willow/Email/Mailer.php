<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Email_Mailer implements Willow_Email_Mailer_Interface, Willow_Registerable_Interface
{

    /**
     * Send the email message
     *
     * @param Willow_Email_Message $message Email message to send
     */
    public function send(Willow_Email_Message $message)
    {
        return $this->getRegistered('default')->send($message);
    }

    /**
     * Specify a specific transport to use
     */
    public function useTransport($transport)
    {
        $this->getRegistered('default')->useTransport($transport);
        return $this;
    }

    /**
     * ...
     */
    public function resetTransport()
    {
        $this->getRegistered('default')->resetTransport();
        return $this;
    }

    /**
     * @var array alias to class mapping
     */
    protected static $_classMap = array();

    /**
     * Register a class under an alias
     *
     * @param string $alias
     * @param mixed $class
     * @return void
     */
    public static function register($alias, $class)
    {
        self::$_classMap[$alias] = $class;

        /**
         * If this is the first mailer we are registering, set it as the default
         */
        if (count(self::$_classMap) === 1)
        {
            self::$_classMap['default'] = $class;
        }
    }

    /**
     * Get instance of class registered under given alias
     *
     * @param string $alias
     * @return object
     * @throws Exception
     */
    public function getRegistered($alias)
    {
        /**
         * Alias is registered
         */
        if (array_key_exists($alias, self::$_classMap))
        {
            if (is_string(self::$_classMap[$alias]))
            {
                $class = self::$_classMap[$alias];
                self::register($alias, new $class());
            }

            $registered = self::$_classMap[$alias];
        }

        /**
         * Alias has not been registered
         */
        else
        {
            throw new Willow_Email_Mailer_Exception(sprintf(
                'Use of unregistered Willow_Email_Mailer alias, "%s"', $alias
            ));
        }

        /**
         * Force $registered type of Willow_Email_Mailer_Interface
         */
        if (($registered instanceof Willow_Email_Mailer_Interface) === false)
        {
            throw new Willow_Email_Mailer_Exception(sprintf(
                'Class "%s" registered as alias to Willow_Email_Mailer::%s ' .
                'must implement Willow_Email_Mailer_Interface',
                get_class($registered),
                $alias
            ));
        }

        /**
         * Return mailer
         */
        return $registered;
    }

    /**
     * Allow direct access to registered mailers via Willow_Email_Mailer::mailerAlias()
     */
    public function __call($method, $args)
    {
        return $this->getRegistered($method);
    }

}
