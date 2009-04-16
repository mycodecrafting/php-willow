<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Session_Handler_Doctrine implements Willow_Session_Handler_Interface
{

	public static function open($savePath, $sessionName);
	public static function close();
	public static function read($sessionId);
	public static function write($sessionId, $data);
	public static function destroy($sessionId);
	public static function gc($maxLifeTime);

}
