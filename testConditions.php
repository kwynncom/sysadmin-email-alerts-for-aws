<?php

function isUbuupEMTest($type) {
	
	return false; // *****
    
    // if (is   *** AWS()) return false;
 
    // return 1;
    
    if ($type === 'levels') return 0;
    if ($type === 'put')    return 1;
    if ($type === 'emst')   return 0;
    
    return false;
    
    
}
