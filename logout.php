<?php

include_once('includes/auth.php');
$auth->logout();
header('Location: login.php');
