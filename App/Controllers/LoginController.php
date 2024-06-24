<?php
namespace Controllers;

use Models\LoginModel;
use Views\LoginView;

class LoginController {

    public function LoginForm() {
        $view = new LoginView();
        $view->loginform();
    }

    public function UserSave() {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $model = new LoginModel();
            $result = $model->authenticate($email, $password);
            if ($result) {
                // Connexion réussie
                $_SESSION['user'] = $email;
                echo "Login successful!";
                header("Location: index.php");
                exit();
            } else {
                // Connexion échouée
                echo "Invalid email or password.";
            }
        } else {
            echo "Email and Password are required.";
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: login");
        exit();
    }
}
?>
