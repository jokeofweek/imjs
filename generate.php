<?php
include_once('includes/auth.php');

if (isset($_POST['password']))
    $hash = $auth->hashPassword($_POST['password']);
?>
<HTML>
<HEAD>
    <TITLE>ImJS Password Generator</TITLE>
    <LINK rel="stylesheet" type="text/css" href="css/login-screen.css"/>
</HEAD>
<BODY>
    <FORM action="generate.php" method="POST">
    <?php if (isset($hash)): ?>
        <P class="error">
            The following hash was generated for password 
            <b><?php echo $_POST['password']?></b>:<br/><br/>
            <?php echo $hash ?>
        </P>
    <?php endif; ?>

          
        <LABEL for="password">Password: </LABEL><BR/>
        <INPUT id="password" name="password" type="text"/><BR/>
        <INPUT id="submit" type="submit" value="Submit"/>
    </FORM>
</BODY>
</HTML>
