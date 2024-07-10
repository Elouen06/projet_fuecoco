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
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && $user['is_confirmed'] == 1 && password_verify($password, $user['pw'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['id_level'] = $user['id_level'];
            return true;
        } else {
            return false;
        }
    }

    public function getUserLevel($email) {
        $stmt = $this->db->prepare("SELECT id_level FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user['id_level'];
    }
}
