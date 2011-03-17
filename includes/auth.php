<?php

include_once('includes/database.php');

class Auth {
    
	// User information
	private $_userData = array();
	// Logged in status
	private $_isLoggedIn = false;
	
    /**
     * Initializes the authentication session.
     */
    function __construct(){
        if (!isset ($_SESSION)) session_start();
        $this->validateSession();
    }
    
    /**
     * Initializes the session variables for a given user ID.
     * @param <Integer> $userID
     * 		The user's ID number.
     */
    function startSession($userID)
    {
    	$_SESSION['userID']    = $userID;
    	$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['userData'] = $this->_userData;

    	$octets = explode(".",$_SERVER['SERVER_ADDR']);
        $_SESSION['userOctet'] = $octets[0];

    }
    
    /**
     * Attempts to validate any existing session. If there
     * is a valid session, it will load the user information
     * to the $_userData variable. If there is an invalid
     * session variable, it will destroy all associated variables.
     */
    function validateSession()
    {
    	// Check to make sure we have the appropriate variables
        if (isset($_SESSION['userID']) && 
            isset($_SESSION['userAgent']) &&
            isset($_SESSION['userOctet']) &&
            isset($_SESSION['userData']))
    	{	
    		// Compare the user agent and destroy if it 
    		if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT']){
    			$this->logout();
    			return;
    		}
    		
    		// Compare the first octet of the users IP
    		$octets = explode(".",$_SERVER['SERVER_ADDR']);
    		if ($_SESSION['userOctet'] != $octets[0]) {
    			$this->logout();
    			return;
    		}
    		
            // Load the user
            $this->_userData = $_SESSION['userData'];
            $this->_isLoggedIn = true;
    	}
    }
    
    /**
     * Attempts to log in the user with a set of credentials.
     * 
     * @param <String> $user The given username
     * @param <String> $pass The given password
     * @return <Integer>
     * 		Result of the login command. See the LOGIN_* defines
     * 		in {@see includes/config.php}.
     */
    function login($user, $pass)
    {
    	global $db;
    	
    	// Check for valid username
    	if (!preg_match("/^[A-Za-z0-9\-_]+$/", $user))
    		return LOGIN_BADNAME;
       
    	// Hash the password and search the database for matching credentials
        $pass = $this->hashPassword($pass);

        $stmt = $db->prepare('SELECT * FROM '.USER_TABLE.' WHERE `username` = ? AND `password` = ?');
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $pass);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row == FALSE)
            return LOGIN_FAILURE;

    	$this->_userData = $row;            
        $this->startSession($this->_userData['id']);
        $this->_isLoggedIn = true;  

        return LOGIN_SUCCESS;
    	
    }
    
    /**
     * Hashes a given string according to the hashing algorithm.     
     * @param <String> $string The string to be hashed
     * @return <String> 
     * 		The hashed string.
     */
    function hashPassword($string)
    {
    	return hash("sha256", AUTH_SALT.$string);
    }
    
    /**
     * Logs the user out and erases all related
     * information, such as sessions and cookies.
     */
    function logout()
    {
    	$this->_userData = array();
    	$this->_isLoggedIn = false;
    	session_unset();
    }
    
    /**
     * Checks whether the current Authentication class has a 
     * user logged in.
     * @return <Boolean>
     * 		Whether the user is logged in or not.
     */
    function isLoggedIn()
    {
    	return $this->_isLoggedIn;
    }
    
    /**
     * This function fetches a value from the current account.
     * Note that if the field does not exist, or there is no user
     * logged in, false will be returned.
     * 
     * @param <String> $fieldName
     * 		The name of the property to fetch
     * @return <*>
     * 		The value of the property, or false if the user
     * 		is not logged in or the property does not exist.
     */
    function getField($fieldName){
    	if (!$this->_isLoggedIn || empty($this->_userData) || !isset($this->_userData[$fieldName]))
    		return false;
    	else
    		return $this->_userData[$fieldName];
    }
    
}

// Global object
$auth = new Auth();
