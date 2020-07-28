<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/creds.php');
require_once('testConditions.php');

function getAWSCPUAlertLevels($kin) {
    $ls = [ 
	    'deall'    => ['live' => 300, 'test' =>  300],
	    'duper'    => ['live' =>  80, 'test' =>   10],
	    'gpm'      => ['live' =>   2, 'test' =>   -1],
	    'cpud'     => ['live' =>   1, 'test' =>   -1]
	];
    
    if (isUbuupEMTest('levels')) $k1 = 'test';
    else			 $k1 = 'live';
    
    return $ls[$kin][$k1];
}

function getUpEmailClearanceURLPrefix() {
    if (!isAWS()) return kwynn_creds::get('upemail_url_doc_root', 'url');
    return 'https://kwynn.com/t/20/07/upemail/';
}

function getUpPath() {
    static $sfx = '/ubuup/runBin.php';
    $p = __DIR__ . '/..';
    if (isAWS()) $p .= '/../05';
    $p.= $sfx;
    return $p;
}

function getAWSCPUPath() {
    static $sfx = '/cpu/get/get.php';
    $p = __DIR__ . '/..';
    if (isAWS()) $p .= '/../../9/10';
    $p .= $sfx;
    return $p;
}
