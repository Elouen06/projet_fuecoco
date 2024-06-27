<?php
namespace Controllers;

use Models\RegisterModel;
use Views\RegisterView;

class RegisterController {
    protected $registerModel; 
    protected $registerView;
    
    public function __construct() {
        $this->registerModel = new RegisterModel(); 
        $this->registerView = new RegisterView(); 
    }

    public function RegisterForm() {
        $this->registerView->initForm();
    }

    public function UserSave() {
        $this->registerModel->createUser();
    }

    public function ConfirmEmail() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $success = $this->registerModel->confirmUser($token);

            if ($success) {
                echo "Email confirmed successfully! You can now log in.";
            } else {
                echo "Invalid or expired token.";
            }
        } else {
            echo "Token is required.";
        }
    }
}

?>
