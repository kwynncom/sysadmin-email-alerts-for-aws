<?php

require_once('/opt/kwynn/kwutils.php');
require_once(getAWSCPUPath());

function getAWS() { 
    $ca = $na = [];
    
    $ca = aws_cpu::awsMRegGet(0   , ['cpu'], 1, 1);
    $na = aws_cpu::awsMRegGet(1/24, ['net'], 1, 1);

    return ['cpu' => $ca, 'net' => $na];
}