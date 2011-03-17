<?php

/*
 * MySQL Connection Parameters
 */
define('MYSQL_HOST', 'localhost');
define('MYSQL_DB', 'imjs');
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');

/*
 * MySQL Table Parameters
 */ 
define('USER_TABLE', 'user');
define('MESSAGE_TABLE', 'message');

/*
 * Authentication Parameters
 */
define('AUTH_SALT', '25Az;4ffdsfv43rV$#?r.213?1'); 
define('LOGIN_SUCCESS', 1);
define('LOGIN_FAILURE', 0);
define('LOGIN_BADNAME', -1);
