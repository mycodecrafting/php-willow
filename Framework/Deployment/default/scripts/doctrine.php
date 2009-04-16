<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


$_SERVER['HTTP_HOST'] = 'my.hostname.tld';

require dirname(__FILE__) . '/../../../App/Bootstrap/Cli.php';

$cli = new Doctrine_Cli(Willow_Whiteboard::get('doctrine_config'));

$cli->run($_SERVER['argv']);
