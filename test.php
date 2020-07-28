<?php

require_once('config.php');
require_once('/opt/kwynn/email.php');

class ubuup_tests {
    public static function doit() {
	$fus = ['getUpPath', 'getAWSCPUPath'];
	foreach($fus as $fu) {
	    $fi = $fu();
	    kwas(file_exists($fi), "$fi from $fu does not exist\n");
	}
	
	kwas(method_exists('kwynn_creds', 'get'), 'new creds::get does not exist');

	$emr = kwynn_email::test();
	
	if ($emr) echo 'Test email sent' . "\n";
	
	
	echo 'OK - all OK' . "\n";
		
    }
}

if (didCLICallMe(__FILE__)) ubuup_tests::doit();
