<?php

require_once('/opt/kwynn/kwcod.php');
require_once('/opt/kwynn/kwutils.php');
require_once('/opt/kwynn/email.php');
require_once('dao.php');
require_once('ubuup.php');
require_once('aws.php');

putSysEvents();

function putSysEvents() {
    $ubu = getUbuup();
    $du  = duper();
    $aws = getAWS();
    $dao = new dao_sysevents();
}


function duper() {
    $raw = disk_free_space(__DIR__) / disk_total_space(__DIR__);
    $p = (1 - $raw) * 100;
    $rnd = round($p, 2);
    return $rnd;
}
