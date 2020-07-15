<?php

require_once('/opt/kwynn/kwutils.php');

class dao_sysstatus extends dao_generic {
    
    const db = 'sysstatus';
    
    public function __construct() {
	parent::__construct(self::db);
	$this->scoll    = $this->client->selectCollection(self::db, 'status');
	$this->ecoll    = $this->client->selectCollection(self::db, 'email');
    }
    
    public function put($dat) {
	$now = time();
	$dat['ts'] = $now;
	$dat['r']  = date('r', $now);
	$this->scoll->insertOne($dat);
	
    }
    
    public function get() { return $this->scoll->findOne([], ['sort' => ['ts' => -1]]);   }
    
    public function pute($dat) {
	$this->ecoll->upsert(['ts' => $dat['ts']], $dat);
    }
    
}