<?php

require_once('/opt/kwynn/kwutils.php');

if (iscli()) {
    require_once('main.php');
    exit(0);
}

require_once('out.php');

exit(0);