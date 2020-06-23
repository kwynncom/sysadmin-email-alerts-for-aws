<?php

require_once('/opt/kwynn/kwutils.php');

class dao_sysevents extends dao_generic {
    
    const db = 'sysevents';
    
    public function __construct() {
	parent::__construct(self::db);
	$this->ecoll    = $this->client->selectCollection(self::db, 'events');
    }
    
    public function put($dat) {
	$now = time();
	$dat['ts'] = $now;
	$dat['r']  = date('r', $now);
	$this->ecoll->insertOne($dat);
	
    }
}