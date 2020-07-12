<?php

function isSysTest($type) {
    
    if (isAWS()) return false;
    
    if ($type === 'put') return 1;
    
    if ($type === 'delay') return 3600;
    
    return false;
    
    
}