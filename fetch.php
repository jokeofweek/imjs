<?php

include('includes/auth.php');

// Check that the user is properly logged in and has a valid ID. 
// If he does not, return 1, which tells the client to refresh
if (!$auth->isLoggedIn())
    return 1;

$id = $auth->getField('id');

if (!$id){
    $auth->logout();
    return 1;
}

// Fetch any new messages from the database
$stmt = $db->prepare('SELECT `sender` AS `s`, a.`username` AS `u`, `message` AS `m` FROM '.MESSAGE_TABLE.' m, '.USER_TABLE.' a WHERE `receiver` = ? AND a.`id` = sender ORDER BY `time`');
$stmt->bindValue(1, $auth->getField('id'));
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check to make sure messages were received
if (empty($data))
    return;
else {
    // Echo the messages in JSON format
    echo json_encode($data);

    // Delete once we have fetched the statements
    $db->query('DELETE FROM `'.MESSAGE_TABLE."` WHERE `receiver` = $id");
}
