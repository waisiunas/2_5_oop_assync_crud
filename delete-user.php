<?php require_once('./database/database.php'); ?>

<?php
$form_data = file_get_contents("php://input");
$_POST = json_decode($form_data, true);

if (isset($_POST['submit'])) {
    $id = htmlspecialchars($_POST['id']);

    if ($database->delete('users', $id)) {
        echo json_encode(['success' => 'Magic has been spelled']);
    } else {
        echo json_encode(['error' => 'Magic has failed to spell']);
    }
}
