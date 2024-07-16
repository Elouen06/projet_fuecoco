<?php
require 'vendor/autoload.php';

use Controllers\RegisterController;
use Controllers\LoginController;
use Controllers\HomeController;
use Controllers\ForgotPasswordController;
use Controllers\ReservationController;
use Controllers\PaymentController;
use Controllers\AdminController;

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

    case 'user_reservations':
        $reservationController = new ReservationController();
        $reservationController->userReservations();
        break;
    
    case 'cancel_user_reservation':
        $reservationController = new ReservationController();
        if (isset($_GET['id'])) {
            $reservationController->cancelUserReservation($_GET['id']);
        }
        break;

    case 'admin':
        $adminController = new AdminController();
        $adminController->dashboard();
        break;
    
    case 'admin_cancel_reservation':
        $adminController = new AdminController();
        if (isset($_GET['id'])) {
            $adminController->cancelReservation($_GET['id']);
        }
        break;
    
    case 'admin_add_blocked_dates':
        $adminController = new AdminController();
        $adminController->addBlockedDates();
        break;

    case 'admin_remove_blocked_dates':
        $adminController = new AdminController();
        $adminController->removeBlockedDates();
        break;
}
