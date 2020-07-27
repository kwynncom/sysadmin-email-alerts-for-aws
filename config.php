<?php

require_once('/opt/kwynn/kwutils.php');
require_once('testConditions.php');

function getAWSCPUAlertLevels($kin) {
    $ls = [ 
	    'deall'    => ['live' => 300, 'test' => 3],
	    'duper'    => ['live' =>  80, 'test' =>   80],
	    'gpm'      => ['live' =>   2, 'test' =>    2],
	    'cpud'     => ['live' =>   1, 'test' =>   -1]
	];
    
    if (isUbuupEMTest('levels')) $k1 = 'test';
    else			 $k1 = 'live';
    
    return $ls[$kin][$k1];

}