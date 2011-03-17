<?php if (!isset($auth)) header('Location: index.php'); ?>
<HTML>
<HEAD>
    <TITLE>ImJS</TITLE>
    <LINK rel="stylesheet" type="text/css" href="css/reset.css"/>
    <LINK rel="stylesheet" type="text/css" href="css/main-screen.css"/>
    <SCRIPT type="text/javascript" src="js/jquery-1.5.1.min.js"></SCRIPT>
    <SCRIPT type="text/javascript" src="js/script.js"></SCRIPT>
</HEAD>
<BODY>
    <DIV id="header">
        <IMG src="images/logo.gif" height="101" width="178" alt="imjs logo"/>        
        <A id="logout-link" href="logout.php" alt="Logout">Logout</A>
        <DIV id="spacer"></DIV>
        <UL id="conversation-list"><LI class="tab-link"><a href="#" name="0" id="contacts-link">Users</A></LI></UL>
    </DIV>

    <DIV id="content">
        <P id="conv-0" class="conversation"></P>
    </DIV> 
    
    
    <FORM id="message-form" action="">
        <LABEL for="message">Message: <INPUT id="m" name="m" type="textbox"/></LABEL>
        <INPUT type="submit" value="Submit"/>
    </FORM>

    <UL id="contact-list"></UL>
    
</BODY>
</HTML>
