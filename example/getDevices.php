<?php
include '../src/config.php';
include '../src/PhpSmsgw.php';

try {
	
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Get all enabled devices for sending messages.
    $devices = $smsgw->getDevices();
    print_r($devices);
} catch (Exception $e) {
    echo $e->getMessage();
}