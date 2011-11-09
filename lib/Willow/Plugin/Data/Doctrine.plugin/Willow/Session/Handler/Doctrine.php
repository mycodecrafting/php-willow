<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Session_Handler_Doctrine implements Willow_Session_Handler_Interface
{

    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        Willow_Session::writeClose();
    }

    /**
     * Open Session - retrieve resources
     *
     * @param string $savePath
     * @param string $sessionName
     */
	public function open($savePath, $sessionName)
	{
	    return true;
	}

    /**
     * Close Session - free resources
     */
	public function close()
	{
	    return true;
	}


    /**
     * Read session data
     *
     * @param string $sessionId
     */
	public function read($sessionId)
	{
	    if (($session = Willow_Doctrine_Session::fetch($sessionId)) === false)
	    {
	        return  '';
	    }

        return $session->data;
	}

    /**
     * Write Session - commit data to resource
     *
     * @param string $sessionId
     * @param mixed $data
     */
	public function write($sessionId, $data)
	{
        if (($session = Willow_Doctrine_Session::fetch($sessionId)) === false)
        {
            $session = new Willow_Doctrine_Session();
            $session->id = $sessionId;
        }

        $session->data = $data;

        $session->save();

        return true;
	}

    /**
     * Destroy Session - remove data from resource for
     * given session id
     *
     * @param string $sessionId
     */
	public function destroy($sessionId)
	{
        if (($session = Willow_Doctrine_Session::fetch($sessionId)) !== false)
        {
            $session->delete();
        }

        return true;
	}

    /**
     * Garbage Collection - remove old session data older
     * than $maxLifeTime (in seconds)
     *
     * @param int $maxLifeTime
     */
	public function gc($maxLifeTime)
	{
	    
	}

}
