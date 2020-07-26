<?php

require_once('/opt/kwynn/kwutils.php');

class dao_sysstatus extends dao_generic {
    
    const db = 'sysstatus';
    
    public function __construct() {
	parent::__construct(self::db);
	$this->rcoll    = $this->client->selectCollection(self::db, 'raw');
	$this->ecoll    = $this->client->selectCollection(self::db, 'eval');
	$this->ccoll    = $this->client->selectCollection(self::db, 'clearance');
	
    }
    
    public function cput($dat) {
	$dat['ts'] = intval($dat['ts']);
	$q = $dat;
	unset($q['all']);
	$er = $this->ecoll->findOne($q);
	if (!$er) return;
	unset($dat['nonce']);
	if (!$dat['all']) $dat['s'] = $er['s'];
	$dat['tsem'] = $dat['ts']; unset($dat['ts']);
	$now = time();
	$dat['tscl'] = $now;
	$dat['rcl' ] = date('r', $now);
	$dat['ip']   = $_SERVER['REMOTE_ADDR'];
	$dat['ua']   = $_SERVER['HTTP_USER_AGENT'];
	$dat['isaws'] = $er['isaws'];
	$this->ccoll->upsert($q, $dat);
	if ($dat['all']) $this->ccoll->updateOne($q, ['$unset' => ['s' => 1]]);
	return;
	
    }
    
    public function put($dat) {
	$now = time();
	$dat['ts'] = $now;
	$dat['r']  = date('r', $now);
	$this->rcoll->insertOne($dat);
	
    }
    
    public function get() { return $this->rcoll->findOne([], ['sort' => ['ts' => -1]]);   }
    
    public function pute($dat) {
	$this->ecoll->upsert(['ts' => $dat['ts']], $dat);
    }
    
}