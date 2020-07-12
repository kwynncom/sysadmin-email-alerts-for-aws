<?php

function isSysTest($type) {
    
    if (isAWS()) return false;
    
    if ($type === 'put') return 1;
    
    return false;
    
    
}