<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;

try {
	
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    // Get all enabled devices for sending messages.
    $devices = $smsgw->getDevices();
    print_r($devices);
} catch (Exception $e) {
    echo $e->getMessage();
}
