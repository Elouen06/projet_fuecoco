<?php

require 'vendor/autoload.php';

use Controllers\RegisterController;
use Controllers\LoginController;




$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $loginController = new LoginController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginController->UserSave();
        } else {
            $loginController->LoginForm();
        }
        break;

    case 'logout':
        $loginController = new LoginController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $loginController->logout();
        }
        break;

    case 'inscription':
        $registerController = new RegisterController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $registerController->UserSave();
        } else {
            $registerController->RegisterForm();
        }
        break;

    case 'admin':
        // Ajouter la logique pour l'administration ici
        break;

    case 'reservation':
        // Ajouter la logique pour les réservations ici
        break;

    default:
        echo "Action non reconnue.";
        break;
}
?>
