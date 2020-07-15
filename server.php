<?php

require_once('/opt/kwynn/kwutils.php');

new upEmailClear();

class upEmailClear {
    
    public function __construct() {
	$p = $this->goodParamsOrDie();
	$this->clear($p);
	
    }

function clear($p) {
    
    
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
    
    $ret = get_defined_vars();
    return $ret;
}
}