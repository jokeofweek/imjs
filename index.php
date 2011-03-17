<?php

include_once('includes/auth.php');

if ($auth->isLoggedIn())
    include_once('chat.php');
else    
    header('Location: login.php');
