<?php require_once('./database/database.php'); ?>

<?php
$form_data = file_get_contents("php://input");
$_POST = json_decode($form_data, true);

if(isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $id = htmlspecialchars($_POST['id']);

    if(empty($name)) {
        echo json_encode(['nameError' => 'Please provide your name from PHP']);
    } elseif(empty($email)) {
        echo json_encode(['emailError' => 'Please provide your email from PHP']);
    } else {
        if($database->is_email_already_exists($email, $id)) {
            echo json_encode(['emailError' => 'Email already exists from PHP']);
        } else {
            $data = [
                'name' => $name,
                'email' => $email,
            ];

            if($database->update('users', $data, $id)) {
                echo json_encode(['success' => 'Magic has been spelled!']);
            } else {
                echo json_encode(['failed' => 'Magic has failed to spell!']);
            }
        }
    }
}
