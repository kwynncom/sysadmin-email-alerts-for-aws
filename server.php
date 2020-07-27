<?php

require_once('/opt/kwynn/kwutils.php');
require_once('dao.php');

new upEmailClear();

class upEmailClear {
    
    public function __construct() {
	$p = $this->goodParamsOrDie();
	$this->clear($p);
	
    }

function clear($p) {
    $dao = new dao_sysstatus();
    $dao->cput($p);
    
    header('Content-Type: text/plain');
    echo('OK - email reset');
    
}
    
function goodParamsOrDie() {
    
    kwas(      isset($_REQUEST['ts'])
	    && isset($_REQUEST['nonce']), 'bad params');
    
    $ts =    $_REQUEST['ts'];
    $nonce = $_REQUEST['nonce'];
    
    kwas(is_numeric($ts), 'bad param 0049 upEC()');
    strtotimeRecent($ts, 'bad timestamp upEmailClear()');
    
    kwas(preg_match('/^[0-9A-Za-z]{1,60}$/', $nonce), 'bad nonce 0051 upEC()');
    
    $all = false;
    if (isset($_REQUEST['all']) 
	&&    $_REQUEST['all'] === '1') $all = true;
    
    if (0) {
	kwas(isset($_REQUEST['isaws']), 'no aws param goodPorDie upemail');
	$isaws   = $_REQUEST['isaws'];
	kwas($isaws === 'Y' || $isaws === 'N', 'bad aws value goodpord upemail');
    }
    
    $ret = get_defined_vars();
    return $ret;
}
}