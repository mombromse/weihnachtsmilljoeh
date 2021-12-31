<?php

ini_set("max_execution_time", -1);

require("./src/weihnachtsmilljoeh.php");

use Kotori\Weihnachtsmilljoeh as WM;

$wm = new WM(9.223372036854770*pow(10,18),9.223372036854775*pow(10,18));
$wm->calculate();

?>