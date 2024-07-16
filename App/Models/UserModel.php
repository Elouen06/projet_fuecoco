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
        $fbe = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $fbe->bindParam(':email', $email);
        $fbe->execute();
        return $fbe->fetch(\PDO::FETCH_ASSOC);
    }

    public function storeResetToken($userId, $token) {
        $srt = $this->db->prepare("INSERT INTO password_resets (user_id, token, created_at) VALUES (:user_id, :token, NOW())");
        $srt->bindParam(':user_id', $userId);
        $srt->bindParam(':token', $token);
        $srt->execute();
    }

    public function validateResetToken($token) {
        $vrt = $this->db->prepare("SELECT user_id FROM password_resets WHERE token = :token AND created_at >= NOW() - INTERVAL 1 HOUR");
        $vrt->bindParam(':token', $token);
        $vrt->execute();
        $vrtresult = $vrt->fetch(\PDO::FETCH_ASSOC);
        return $vrtresult['user_id'] ?? false;
    }

    public function updatePassword($userId, $hashedPassword) {
        $up = $this->db->prepare("UPDATE users SET pw = :pw WHERE id = :id");
        $up->bindParam(':pw', $hashedPassword);
        $up->bindParam(':id', $userId);
        $up->execute();

        // Supprimer les tokens de réinitialisation utilisés
        $up = $this->db->prepare("DELETE FROM password_resets WHERE user_id = :id");
        $up->bindParam(':id', $userId);
        $up->execute();
    }
}
