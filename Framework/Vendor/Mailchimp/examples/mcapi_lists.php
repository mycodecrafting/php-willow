<?php
/**
This Example shows how to pull the Members of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->lists();

if ($api->errorCode){
	echo "Unable to load lists()!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Your lists:\n";
	foreach ($retval as $list){
		echo "Id = ".$list['id']." - ".$list['name']." - ".$list['web_id']."\n";
		echo "\tSub = ".$list['member_count']."\tUnsub=".$list['unsubscribe_count']."\tCleaned=".$list['cleaned_count']."\n";
	}
}

?>
