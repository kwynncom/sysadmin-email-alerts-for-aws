<?php

require_once('/opt/kwynn/kwutils.php');

require_once(getAWSPath());

function getAWSPath() {
    if (!isAWS()) return __DIR__ . '/..' . '/cpu/get/get.php';
}

class aws_periodic_metrics {
    
    public function __construct() {
	self::getAWS();
    }

private static function getAWS() {
    $narr = $carr = [];
    aws_cpu::cliGet('2020-06-21', '2020-06-24', 86400, 'net', $narr);
    aws_cpu::cliGet('2020-06-21', '2020-06-24', 86400, 'cpu', $carr);
    return;
}

private static function getcpu() {
    
}

}

function getAWS() { new aws_periodic_metrics(); }