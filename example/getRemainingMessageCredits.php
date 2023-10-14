<?php
include '../src/config.php';
require '../src/PhpSmsgw.php';

use Irteel\Smsgw\PhpSmsgw;


try {
	$smsgw=new PhpSmsgw(SERVER,API_KEY);
	
    $credits = $smsgw->getBalance();
    echo "Message Credits Remaining: {$credits}";
} catch (Exception $e) {
    echo $e->getMessage();
}
