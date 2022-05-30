<?php
session_name('sn_'.rand(1000000000,1999999999));
session_start();
require_once 'base/common.php';
$GLOBALS['title']='HREC GMCS';
head();
echo '<h2 class="text-center text-danger">New HREC</h2>';
login();
tail();
?>
