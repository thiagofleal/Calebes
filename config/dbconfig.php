<?php

use Tonight\Data\Driver\MySQL;

global $dbconfig;

$dbconfig = array();
$dbconfig[] = MySQL::class;
$dbconfig[] = 'mysql:host=localhost;dbname=caleb_mission;';
$dbconfig[] = 'root';
$dbconfig[] = '';