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
	unset($er['nonce'], $dat['nonce'], $q['nonce']);
	if (!$dat['all']) $dat['s'] = $er['s'];
	$dat['tsem'] = $dat['ts']; unset($dat['ts']);
	$now = time();
	$dat['tscl'] = $now;
	$dat['rcl' ] = date('r', $now);
	$dat['ip']   = $_SERVER['REMOTE_ADDR'];
	$dat['ua']   = $_SERVER['HTTP_USER_AGENT'];
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
    
    public function emailAndClearanceHistory($sin) {
	foreach($sin as $k => $v) if ($v === false) $qfs[] = $k;
	
	if (count($qfs) === 1) $q = ["s.$qfs[0]" => false];
	else foreach($qfs as $f) $orq[] = ["s.$f" => false];
	if (!isset($q)) $q['$or'] = $orq;
	
	if (isUbuupEMTest('emst')) $q2 = $q;
	else			   $q2 = ['$and' => [$q, ['email_status' => true]] ];
	
	$per = $this->ecoll->findOne($q2, ['sort' => ['ts' => -1]]);
	
	if (!$per) return false;
	
	$q3['$or' ][] = ['all' => true];
	$q3['$or' ][] = $q;
	$q4['$and'][] = $q3;
	$q4['$and'][] = ['tscl' => ['$gte' => $per['ts']]];
	
	$cr  = $this->ccoll->findOne($q4, ['sort' => ['tscl' => -1]]);
	
	return ['e' => $per, 'c' => $cr];
    }
    
}