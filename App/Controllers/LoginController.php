<?php
namespace Controllers;

use Models\LoginModel;
use Views\LoginView;

class LoginController {

    public function LoginForm() {
        $loginView = new LoginView();
        $loginView->loginform();
    }

    public function UserSave() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            if (isset($_POST['email']) && isset($_POST['password'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $model = new LoginModel();
                $result = $model->authenticate($email, $password);
                if ($result) {
                    // Connexion réussie
                    $_SESSION['user'] = $email;
                    $_SESSION['id_level'] = $model->getUserLevel($email);
                    echo "Login successful!";
                    header("Location: ./accueil");
                    exit();
                } else {
                    // Connexion échouée
                    echo "Invalid email or password.";
                }
            } else {
                echo "Email and Password are required.";
            }
        } else {
            echo "Invalid CSRF token.";
        }
    }

    public function logout() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            session_unset();
            session_destroy();
            header("Location: ./accueil");
            exit();
        } else {
            echo "Invalid CSRF token.";
        }
    }
}
