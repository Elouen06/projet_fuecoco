<?php
namespace Models;

use App\Database;

class UserModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function storeResetToken($userId, $token) {
        $stmt = $this->db->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (:user_id, :token, NOW())");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
    }

    public function validateResetToken($token) {
        $stmt = $this->db->prepare("SELECT user_id FROM password_resets WHERE token = :token AND created_at >= NOW() - INTERVAL 1 HOUR");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['user_id'] ?? false;
    }

    public function updatePassword($userId, $hashedPassword) {
        $stmt = $this->db->prepare("UPDATE users SET pw = :pw WHERE id = :id");
        $stmt->bindParam(':pw', $hashedPassword);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        // Supprimer les tokens de réinitialisation utilisés
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE user_id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
    }
}
