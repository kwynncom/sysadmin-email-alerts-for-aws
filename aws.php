<?php

require_once('/opt/kwynn/kwutils.php');
require_once(getAWSCPUPath());

function getAWS() { 
    $ca = $na = [];
    
	if (!ispkwd()) {
		$ca = aws_cpu::awsMRegGet(0   , ['cpu'], 1, 1);
		$na = aws_cpu::awsMRegGet(1/24, ['net'], 1, 1);
	       return ['cpu' => $ca, 'net' => $na];
	} else return ['cpu' =>   0, 'net' => 100 * M_BILLION];
}
