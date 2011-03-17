<?php

include_once('includes/config.php');

$db = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_DB,MYSQL_USER, MYSQL_PASS);

