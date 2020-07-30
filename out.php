<?php

function ueo($msg, $type = false) {
    if (!iscli()) return;
    
    if ($type === 'emailRes') {
	if ($msg === true)   return ueo2('email sent');
	if ($msg === false)  return ueo2('email failed');
	if (is_string($msg)) return ueo2('email result: ' . $msg);
	return ueo2('unknown emailRes type to ueo()');
    }
    
    return ueo2($msg);
    
}

function ueo2($msg) {
    echo $msg . "\n";    
}