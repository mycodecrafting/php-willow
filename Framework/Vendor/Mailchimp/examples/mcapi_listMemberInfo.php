<?php
/**
This Example shows how to pull the Info for a Member of a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);

$retval = $api->listMemberInfo( $listId, $my_email );

if ($api->errorCode){
	echo "Unable to load listMemberInfo()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Member info:\n";
    //below is stupid code specific to what is returned
    //Don't actually do something like this.
    foreach($retval as $k=>$v){
        if (is_array($v)){
            //handle the merges
            foreach($v as $l=>$w){
                echo "\t$l = $w\n";
            }
        } else {
            echo "$k = $v\n";
        }
    }
}

?>
