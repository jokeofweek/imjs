<?php

include_once('includes/auth.php');

if (!$auth->isLoggedIn())
    return;

$stmt = $db->prepare('SELECT id AS `i`, username AS `u` FROM '.USER_TABLE.' ORDER BY username');
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
