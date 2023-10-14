<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;


try {
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
	
    // Get a USSD request using the ID.
    /*$ussdRequest = $smsgw->getUssdRequestByID(1);
    print_r($ussdRequest);*/
    
    // Get USSD requests with request text "*150#" sent in last 24 hours.
    $ussdRequests = $smsgw->getUssdRequests("#123#", null, null, time() - 86400);
    print_r($ussdRequests);
} catch (Exception $e) {
    echo $e->getMessage();
}
