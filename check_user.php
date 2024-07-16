<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Models\RegisterModel;

header('Content-Type: application/json');

$email = $_GET['email'] ?? '';
$username = $_GET['username'] ?? '';

$registerModel = new RegisterModel();

$response = [
    'emailExists' => $registerModel->emailExists($email),
    'usernameExists' => $registerModel->usernameExists($username)
];

echo json_encode($response);
?>
