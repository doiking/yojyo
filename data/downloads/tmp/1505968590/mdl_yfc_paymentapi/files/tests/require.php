<?php
//EC-CUBE
$HOME = realpath(dirname(__FILE__)) . '/../../../../../';
require_once "{$HOME}html/require.php";
//Module
$MDLDIR = realpath(dirname(__FILE__)) . '/../';
require_once "{$MDLDIR}/inc/include.php";
set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/local/lib/php');
require_once "{$MDLDIR}tests/class/Common_TestCase.php";

