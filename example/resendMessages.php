<?php
include '../src/config.php';
include '../src/PhpSmsgw.php';


try {
	
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Resend a message using the ID.
    $msg = $smsgw->resendMessageByID(1);
    print_r($msg);

    // Get messages using the Group ID and Status.
    $msg = $smsgw->resendMessagesByGroupID('LV5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871', 'Failed');
    print_r($msgs);
    
    // Resend pending messages in last 24 hours.
    $msg = $smsgw->resendMessagesByStatus("Pending", null, null, time() - 86400);
    
    // Resend pending messages sent using SIM 1 of device ID 8 in last 24 hours.
    $msg = $smsgw->resendMessagesByStatus("Received", 8, 0, time() - 86400);
    print_r($msgs);
} catch (Exception $e) {
    echo $e->getMessage();
}