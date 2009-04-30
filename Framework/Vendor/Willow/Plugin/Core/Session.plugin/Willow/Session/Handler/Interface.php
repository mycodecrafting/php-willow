<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


interface Willow_Session_Handler_Interface
{

	public function open($savePath, $sessionName);
	public function close();
	public function read($sessionId);
	public function write($sessionId, $data);
	public function destroy($sessionId);
	public function gc($maxLifeTime);

}
