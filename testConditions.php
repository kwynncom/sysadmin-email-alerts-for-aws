<?php

function isUbuupEMTest($type) {
    
    if (isAWS()) return false;
 
    // return 1;
    
    if ($type === 'levels') return 1;
    if ($type === 'put')    return 1;
    if ($type === 'emst')   return 1;
    
    return false;
    
    
}
