<?php
/**
This Example shows how to Subscribe a New Member to a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

/** 
Note that if you are not passing merge_vars, you will still need to
pass a "blank" array. That should be either:
	$merge_vars = array('');
	   - or -
	$merge_vars = '';

Specifically, this will fail:
	$merge_vars = array();

Or pass the proper data as below...
*/
$merge_vars = array('FNAME'=>'Test', 'LNAME'=>'Account', 
                    'INTERESTS'=>'');
// By default this sends a confirmation email - you will not see new members
// until the link contained in it is clicked!
$retval = $api->listSubscribe( $listId, $my_email, $merge_vars );

if ($api->errorCode){
	echo "Unable to load listSubscribe()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Returned: ".$retval."\n";
}

?>
