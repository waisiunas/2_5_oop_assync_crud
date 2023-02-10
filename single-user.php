<?php require_once('./database/database.php'); ?>

<?php
$form_input = file_get_contents("php://input");
$_POST = json_decode($form_input, true);

if (isset($_POST['submit'])) {
    $user_id = $_POST['id'];

    $user = $database->show_single('users', $user_id);
    echo json_encode($user);
}