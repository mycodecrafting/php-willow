<?php
/**
This Example shows how to pull the Members of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->listMembers($listId, 'subscribed', null, 0, 5000 );

if ($api->errorCode){
	echo "Unable to load listMembers()!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
	echo "Members returned: ". sizeof($retval). "\n";
} else {
	echo "Members returned: ". sizeof($retval). "\n";
	foreach($retval as $member){
	    echo $member['email']." - ".$member['timestamp']."\n";
	}
}

?> 
