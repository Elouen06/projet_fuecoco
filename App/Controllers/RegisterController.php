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
}
?>
