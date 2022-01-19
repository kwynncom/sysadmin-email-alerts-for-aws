<?php

require_once('/opt/kwynn/kwcod.php');
require_once('/opt/kwynn/kwutils.php');
require_once('out.php');
require_once('/opt/kwynn/email.php');
require_once('dao.php');
require_once('ubuup.php');
require_once('aws.php');
require_once('testConditions.php');
require_once('config.php');
require_once('emailConditions.php');

new sysStatus();

class sysStatus {

    const cpud         =   1;

public function __construct() {

    $this->dao = new dao_sysstatus();
    if (!isUbuupEMTest('put')) $this->putSysStatus();
    $r = $this->eval();
    $this->actOnEval($r);
}

private static function genBody($r, $a, $a2) {
    $ht  = '';
    $ht .= "<p><a href='$a' >clear these errs</a></p>\n";
    $ht .= "<p><a href='$a2'>clear all errs</a></p>\n";
    $ht .= '<pre>';
    $ht .= $r;
    $ht .= '</pre>';
    
    return $ht;
    
}

private function actOnEval($e) {
    
    if ($e['stot'] === true) {
	ueo('OK - all tests passed');
	return;
    }
    
    ueo('1+ tests failed');
    
    if (!upemail_conditions::shouldSend($e['s'], $this->dao)) {
	ueo('no emails will be sent due to conditions');
	return;
    }
    
    
    unset($e['nete'], $e['cpue']);
    
    $d = $e;
    $now = time();
    $d['ts'] = $now;
    $d['r']  = date('r', $now);
    $d['email_status'] = 'pre';
    $d['nonce'] = base62(6);
    
    $a  = self::getLink($now, $d['nonce']);
    $a2 = self::getLink($now, $d['nonce'], 1);
    
    $this->dao->pute($d);
    
    $r = print_r($e, 1);
    
    $eo = new kwynn_email();
    
    $body = self::genBody($r, $a, $a2);
    
    $eres = $eo->smail($body, 'sys check fail', 1);
    
    ueo($eres, 'emailRes');
    
    $d['email_status'] = $eres;
    $this->dao->pute($d);
    
    return;
}

private static function getLink($q, $q2, $all = false) {
    $a = getUpEmailClearanceURLPrefix() . 'server.php?ts='; 
    // else 
    
    $a .= $q;
    $a .= '&nonce=' . $q2;
    if ($all) $a .= '&all=1';
    
    // $a .= '&XDEBUG_SESSION_START=netbeans-xdebug';
    
    return $a;
}

private static function evalDelay($r) {
    $dAllow = getAWSCPUAlertLevels('deall'); unset($delayt);   
    
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
    
    unset($cpud, $netd, $thisd);
    
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
        
    $s['cpu'  ] = $cpu > $maxpos  - getAWSCPUAlertLevels('cpud'); unset($maxpos);
    $s['net'  ] = $net < getAWSCPUAlertLevels('gpm');
    $s['space'] = $du < getAWSCPUAlertLevels('duper');
    $s['ubuup'] = $ubuup === false;

    $stot = true;
    foreach($s as $v) if ($v !== true) $stot = false; unset($v);
    
    $ret = get_defined_vars();
    
    } catch (Exception $ex) {
	$x = 17;
    }
    
    return $ret;
}

private function putSysStatus() {
    $ubuup = getUbuup();
    $duper  = self::duper();
    $aws = getAWS();
    // $mid = self::getMID();
    $status = get_defined_vars(); 
    $this->dao->put($status);
    
}

public static function duper() {
    $raw = disk_free_space(__DIR__) / disk_total_space(__DIR__);
    $p = (1 - $raw) * 100;
    $rnd = round($p, 2);
    return $rnd;
}
}