<?php

function isUbuupEMTest($type) {
    
    if (isAWS()) return false;
 
    // return 1;
    
    if ($type === 'levels') return 0;
    if ($type === 'put')    return 0;
    if ($type === 'emst')   return 0;
    
    return false;
    
    
}
