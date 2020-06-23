<?php

require_once(getUpPath('runBin.php'));

function getUpPath($file) {
    if (!isAWS()) return __DIR__ . '/../ubuup/' . $file;
    
}

function getUbuup() {
    
    $upo = new runUpdateBin();
    $upa = (array) $upo; unset($upo);
    $vital = isset($upa['vital']) ? $upa['vital'] : true;
    return $vital;
}
