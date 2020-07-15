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
    const gpm          =  10;
    // const cpu          = 1

public function __construct() {

    $this->dao = new dao_sysstatus();
    if (!isSysTest('put')) $this->putSysStatus();
    $this->eval();
}

private static function evalDelay($r) {
    $delayt = isSysTest('delay');
    if ($delayt !== false) $dAllow = $delayt;
    else                   $dAllow = self::delayAllowed; unset($delayt);   
    
    $now    = time();
    $thisd = $now - $r['ts'];
    
    $nete  = $r['aws']['net']['end_exec_ts'];
    $cpue  = $r['aws']['cpu']['end_exec_ts'];
    $netd  = $now - $nete;
    $cpud  = $now - $cpue;   unset($now, $r); 
    
    $s = [];
        
    $s['dthis'] = $thisd <= $dAllow; 
    $s['dnet' ] = $netd  <= $dAllow;
    $s['dcpu' ] = $cpud  <= $dAllow; unset($dAllow);
    
    return get_defined_vars();
}


private function eval() {
    try {
    
    $r = $this->dao->get();
    $edr = self::evalDelay($r);
    extract($edr); unset($edr);
    
    $cpu = $r['aws']['cpu']['cpu'];
    $net =  $r['aws']['net']['gpm'];
    $du  = $r['duper'];
    $ubuup = $r['ubuup']; 
    $maxpos = $r['aws']['cpu']['max_possible_cpu']; unset($r);
        
    $s['cpu'  ] = $cpu > $maxpos  - 1; unset($maxpos);
    $s['net'  ] = $net < self::gpm;
    $s['space'] = $du < self::duper;
    $s['ubuup'] = $ubuup === false;
     
    } catch (Exception $ex) {
	$x = 17;
    }
    
    $ret = get_defined_vars();
    
    return $ret;
    
    
}

private function putSysStatus() {
    $ubuup = getUbuup();
    $duper  = self::duper();
    $aws = getAWS();
    $mid = self::getMID();
    $status = get_defined_vars(); 
    $this->dao->put($status);
    
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