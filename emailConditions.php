<?php

class upemail_conditions {
    public static function shouldSend($sin, $dao) {
	$to = new self($sin, $dao);
	return $to->check;
	
    }
    
    private function __construct($sin, $dao) {
	$this->dao = $dao;
	$this->sta = $sin;
	$this->check = $this->check();
	
    }
    
    private function check() {
	$dao = $this->dao;
	$ss   = $this->sta;
	$r = $dao->emailAndClearanceHistory($ss); unset($dao);
	if (!$r) return true;
	if    ($r['c']['all']) return true;
	$es  = $r['e']['s'];
	$cs  = $r['c']['s']; unset($r);
	
	foreach($ss as $sk => $s) {
	    if ($s) continue;
	    if (!isset($es[$sk]))	   return true;
	    if (       $es[$sk] !== $s)	   return true;
	    if (!isset($cs[$sk]))	   return true;
	    if (       $cs[$sk] === false) return true;
	}
		
	return false;
    }
    
}