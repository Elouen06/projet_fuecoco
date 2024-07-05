<?php

require 'vendor/autoload.php';

use Controllers\RegisterController;
use Controllers\LoginController;
use Controllers\HomeController;
use Controllers\ForgotPasswordController;
use Controllers\ReservationController;
use Controllers\PaymentController;

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }
}

switch ($action) {

    default:
        $controller = new HomeController();
        $controller->index();
        break;

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

    case 'forgot_password':
        $forgotPasswordController = new ForgotPasswordController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $forgotPasswordController->sendResetLink();
        } else {
            $forgotPasswordController->forgotPasswordForm();
        }
        break;

    case 'reset_password':
        $forgotPasswordController = new ForgotPasswordController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $forgotPasswordController->resetPassword();
        } else if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $forgotPasswordController->resetPasswordForm($token);
        } else {
            echo "Invalid token.";
        }
        break;
    
    case 'confirm_email':
        $registerController = new RegisterController();
        $registerController->ConfirmEmail();
        break;

        case 'reserve':
            $reservationController = new ReservationController();
            $reservationController->reserve();
            break;
    
        case 'reservation_summary':
            $reservationController = new ReservationController();
            $reservationController->reservationSummary();
            break;
    
        case 'confirm_reservation':
            $reservationController = new ReservationController();
            $reservationController->confirmReservation();
            break;
    
        case 'payment_choice':
            $paymentController = new PaymentController();
            $paymentController->paymentChoice();
            break;
    
        case 'process_payment':
            $paymentController = new PaymentController();
            $paymentController->processPayment();
            break;
    
        case 'mes_reservations':
            // Ajouter le code pour afficher les réservations de l'utilisateur
            // Par exemple:
            // $reservationController = new ReservationController();
            // $reservationController->showReservations();
            break;

    case 'admin':
        // Ajouter la logique pour l'administration ici
        break;
}
?>
