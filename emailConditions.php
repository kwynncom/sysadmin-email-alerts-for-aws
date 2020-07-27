<?php

class upemail_conditions {
    public static function shouldSend($sin, $dao) {
	$to = new self($sin, $dao);
	
    }
    
    private function __construct($sin, $dao) {
	$this->dao = $dao;
	$this->sta = $sin;
	$this->check();
	
    }
    
    private function check() {
	$dao = $this->dao;
	$s   = $this->sta;
	
	$dao->checkClearance($s);
	
	
	
	
    }
    
}