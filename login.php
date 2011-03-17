<?php

include_once('includes/auth.php');

if ($auth->isLoggedIn()) 
    header('Location: index.php');

// Check if we have completed the form , and then attempt to login if the data is valid
if (isset($_POST['submit'])){
    if (isset($_POST['username']) && $_POST['username'] != ''){
        if (isset($_POST['password']) && $_POST['password'] != ''){
            switch($auth->login($_POST['username'], $_POST['password'])){
                case LOGIN_SUCCESS:
                    header('Location: index.php');
                    
                case LOGIN_FAILURE:
                    $error = "No account was found with that username and password.";
                    break;

                case LOGIN_BADNAME:
                    $error = "The username you entered contained invalid characters.";
                    break;
            }
        } else
            $error = 'No password was entered.';
    } else
        $error = 'No username was entered.';
}
?>
<HTML>
<HEAD>
    <TITLE>ImJS Login</TITLE>
    <LINK rel="stylesheet" type="text/css" href="css/login-screen.css"/>
</HEAD>
<BODY>
    <DIV id="container">
        <DIV id="content">
            <IMG src="images/logo.gif" alt="imjs logo"/><br/>
            <FORM id="login" action="login.php" method="POST">
                <?php if (isset($error)) echo '<p class="error">'.$error.'</p>';?>
                <LABEL for="username">Username</LABEL><br/>
                <INPUT id="username" class="textfield" name="username" type="text" /><br/>
                <LABEL for="password">Password</LABEL><br/>
                <INPUT id="password" class="textfield" name="password" type="password" /><br/><br/>
                <INPUT id="submit" name="submit" type="submit" value="Submit"/>
            </FORM>
        </DIV>
    </DIV>
</BODY>
</HTML>
