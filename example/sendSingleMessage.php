<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;



try {
	
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Send a message using the primary device.
    $msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of single message.");

    // Send a message using the Device ID 1.
    $msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of single message.", 1);
    
    // Send a prioritize message using Device ID 1 for purpose of sending OTP, message reply etc…
    $msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of single message.", 1, null, false, null, true);
    
    // Send a MMS message with image using the Device ID 1.
   /* $attachments = "https://example.com/images/footer-logo.png,https://example.com/downloads/sms-gateway/images/section/create-chat-bot.png";
    $msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of single message.", 1, null, true, $attachments);*/
	
    // Send a message using the SIM in slot 1 of Device ID 1 (Represented as "1|0").
    // SIM slot is an index so the index of the first SIM is 0 and the index of the second SIM is 1.
    // In this example, 1 represents Device ID and 0 represents SIM slot index.
    /*$msg = sendSingleMessage("+237695601314", "This is a test of single message.", "1|0");*/
	$msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of single message.", "1|1");
    
    // Send scheduled message using the primary device.
    $msg = $smsgw->sendSingleMessage("+237695601314", "This is a test of schedule feature.", null, strtotime("+2 minutes"));
    print_r($msg);

    echo "Successfully sent a message.";
} catch (Exception $e) {
    echo $e->getMessage();
}
