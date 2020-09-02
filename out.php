<?php // see output check at bottom

require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/isKwGoo.php');
require_once('dao.php');

class upemail_output extends dao_generic {
    
    const db = dao_sysstatus::db;
    
    public static function out($msg, $type = false) {
	if (!iscli()) return;

	if ($type === 'emailRes') {
	    if ($msg === true)   return self::outi('email sent');
	    if ($msg === false)  return self::outi('email failed');
	    if (is_string($msg)) return self::outi('email result: ' . $msg);
	    return self::outi('unknown emailRes type to ueo()');
	}

	return self::outi($msg);	
    }
    
    private static function outi($msg) {
	    static $o = false;
	    echo $msg . "\n";    
	    if (!$o) $o = new self();
	    $o->put($msg);	
    }
    
    private function put($msg) {
	$now = time();
	$d['ts'] = $now;
	$d['r' ] = date('r', $now);
	$d['m' ] = $msg;
	$this->ocoll->insertOne($d);
    }
    
    private function __construct() {
	parent::__construct(self::db);
	$this->ocoll    = $this->client->selectCollection(self::db, 'out');
    }
    
    public static function accessCheck() {
	if (isKwGoo() || isKwDev()) { self::allOutput(); exit(0); }
	$m = 'The following should be overwhelming evidence of Russian collusion: "Nyet!"';
	http_response_code(401);
	die($m);
    }
    
    public static function allOutput() {
	$o = new self();
	$a = $o->ocoll->find([], ['sort' => ['ts' => -1], 'limit' => 50])->toArray();
	
	header('Content-Type: text/plain');
	
	foreach($a as $r) {
	    $d = date( 'h:i A D m/d' , $r['ts']);
	    echo $d . ': ' . $r['m'] . "\n";
	}
	
	if (!$a) echo 'no data';
    }
}

function ueo($msg, $type = false) { upemail_output::out($msg, $type); }

if (!iscli()) upemail_output::accessCheck();
