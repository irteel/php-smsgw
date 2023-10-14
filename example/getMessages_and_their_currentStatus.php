<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;


try {
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Get a message using the ID.
    $msg = $smsgw->getMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID.
    $msg = $smsgw->getMessagesByGroupID(')V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871');
    print_r($msgs);
    
    // Get messages received in last 24 hours.
    $msg = $smsgw->getMessagesByStatus("Received", null, null, time() - 86400);
    
    // Get messages received on SIM 1 of device ID 8 in last 24 hours.
    $msg = $smsgw->getMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}
