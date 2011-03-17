<?php

include('includes/auth.php');

if ( !isset($_POST['r']) || !isset($_POST['m']))
    return;

$receiver = $_POST['r'];
$message = htmlentities($_POST['m']);

if (!is_numeric($receiver))
    return;

// Check that the user is properly logged in and has a valid ID. 
// If he does not, return 1, which tells the client to refresh
if (!$auth->isLoggedIn())
    return 1;

$id = $auth->getField('id');

if (!$id){
    $auth->logout();
    return 1;
}

$stmt = $db->prepare('INSERT INTO `message` (id, sender, receiver, message) VALUES (\'\', ?, ?, ?)');
$stmt->bindValue(1, $id);
$stmt->bindValue(2, $receiver);
$stmt->bindValue(3, $message);
$stmt->execute();

echo 2;
