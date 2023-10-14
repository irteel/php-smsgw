<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;


try {
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Send a USSD request using default SIM of Device ID 1.
    /*$ussdRequest = $smsgw->sendUssdRequest("#123#", 1);
    print_r($ussdRequest);*/
    
    // Send a USSD request using SIM in slot 1 of Device ID 1.
    /*$ussdRequest = $smsgw->sendUssdRequest("*150#", 1, 0);
    print_r($ussdRequest);*/
    
    // Send a USSD request using SIM in slot 2 of Device ID 1.
    $ussdRequest = $smsgw->sendUssdRequest("#123#", 1, 1);
    print_r($ussdRequest);
} catch (Exception $e) {
    echo $e->getMessage();
}
