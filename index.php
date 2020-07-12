<?php

require_once('/opt/kwynn/kwcod.php');
require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/email.php');
require_once('dao.php');
require_once('ubuup.php');
require_once('aws.php');
require_once('testConditions.php');

new sysStatus();

class sysStatus {
    
    const delayAllowed = 300;
    const duper        =  80;
    // const cpu          = 1

public function __construct() {

    $this->dao = new dao_sysstatus();
    if (!isSysTest('put')) $this->putSysStatus();
    $this->eval();
}

private function eval() {
    try {
    $r = $this->dao->get();
    $delayt = isSysTest('delay');
    if ($delayt !== false) $delay = $delayt;
    else                   $delay = self::delayAllowed;
    
    $d = time() - $r['ts'];
    kwas ($d <= $delay, 'system check too old'); unset($d, $delay);

    kwas($r['duper'] < self::duper, 'space is low');
    // kwas($r['aws']['cpu'] 
    
    
    } catch (Exception $ex) {
	$x = 17;
    }
    $x = 2;
    
    
}

private function putSysStatus() {
    $ubu = getUbuup();
    $duper  = self::duper();
    $aws = getAWS();
    $mid = self::getMID();
    $status = get_defined_vars(); unset($ubu, $du, $aws);
    $this->dao->put($status); unset($status);
    
}

private static function getMID() {
    if (isAWS())  return 'aws';
    if (ispkwd()) return 'precisely-kwynn-dev';
    return               'not-aws';
}

public static function duper() {
    $raw = disk_free_space(__DIR__) / disk_total_space(__DIR__);
    $p = (1 - $raw) * 100;
    $rnd = round($p, 2);
    return $rnd;
}
}