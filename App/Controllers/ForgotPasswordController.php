<?php
namespace Controllers;

use Models\ForgotPasswordModel;
use Views\ForgotPasswordView;

class ForgotPasswordController {
    public function forgotPasswordForm() {
        $view = new ForgotPasswordView();
        $view->showForm();
    }

    public function sendResetLink() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $model = new ForgotPasswordModel();
                $success = $model->sendResetLink($email);

                if ($success) {
                    echo "An email has been sent with instructions to reset your password.";
                } else {
                    echo "There was a problem sending the email. Please try again.";
                }
            } else {
                echo "Email is required.";
            }
        } else {
            echo "Invalid CSRF token.";
        }
    }

    public function resetPasswordForm($token) {
        $view = new ForgotPasswordView();
        $view->showResetForm($token);
    }

    public function resetPassword() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            if (isset($_POST['token']) && isset($_POST['password'])) {
                $token = $_POST['token'];
                $new_password = $_POST['password'];

                $model = new ForgotPasswordModel();
                $user = $model->validateResetToken($token);

                if ($user) {
                    $model->updatePassword($token, $new_password);
                    echo "Password has been reset successfully.";
                } else {
                    echo "Invalid reset token.";
                }
            } else {
                echo "Token and Password are required.";
            }
        } else {
            echo "Invalid CSRF token.";
        }
    }
}
