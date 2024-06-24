<?php
namespace Models;

use App\Database;

class RegisterModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    } 

    public function createUser() {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash le mot de passe avant de le stocker
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, pw) VALUES (:username, :email, :pw)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pw', $hashedPassword);
            $stmt->execute();

            echo "<h1>Utilisateur créé avec succès</h1>";
        } catch (\PDOException $e) {
            echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>
