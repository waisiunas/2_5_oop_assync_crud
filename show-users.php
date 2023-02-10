<?php require_once('./database/database.php'); ?>

<?php

$users = $database->show('users');
echo json_encode($users);
