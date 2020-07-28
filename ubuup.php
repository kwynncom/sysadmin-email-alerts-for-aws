<?php
require_once('config.php');
require_once(getUpPath());

function getUbuup() {
    
    $upo = new runUpdateBin();
    $upa = (array) $upo; unset($upo);
    $vital = isset($upa['vital']) ? $upa['vital'] : true;
    return $vital;
}
