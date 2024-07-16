<?php
namespace Models;

use App\Database;

class LoginModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function authenticate($email, $password) {
        $auth = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $auth->bindParam(':email', $email);
        $auth->execute();
        $user = $auth->fetch(\PDO::FETCH_ASSOC);

        if ($user && $user['is_confirmed'] == 1 && password_verify($password, $user['pw'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['id_level'] = $user['id_level'];
            return true;
        } else {
            return false;
        }
    }

    public function getUserLevel($email) {
        $gul = $this->db->prepare("SELECT id_level FROM users WHERE email = :email");
        $gul->bindParam(':email', $email);
        $gul->execute();
        $user = $gul->fetch(\PDO::FETCH_ASSOC);
        return $user['id_level'];
    }
}
