<?php
include '../src/config.php';
include '../src/PhpSmsgw.php';

try {
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Add a new contact to contacts list 1 or resubscribe the contact if it already exists.
    $contact = $smsgw->addContact(1, "+237695601314", "Test", true);
    print_r($contact);
    
    // Unsubscribe a contact using the mobile number.
    $contact = $smsgw->unsubscribeContact(1, "+237695601314");
    print_r($contact);
} catch (Exception $e) {
    echo $e->getMessage();
}