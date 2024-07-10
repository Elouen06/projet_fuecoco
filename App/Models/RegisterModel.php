<?php
namespace Models;

use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        $confirmation_token = bin2hex(random_bytes(32));
        $id_lvl = 1; // Attribuer id_lvl à 1 par défaut

        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, pw, id_level, confirmation_token) VALUES (:username, :email, :pw, :id_level, :confirmation_token)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pw', $hashedPassword);
            $stmt->bindParam(':id_level', $id_lvl); // Assigner le niveau de l'utilisateur
            $stmt->bindParam(':confirmation_token', $confirmation_token);
            $stmt->execute();

            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'mercierelouen@gmail.com'; // Remplacez par votre adresse email SMTP
                $mail->Password = 'ktodmftjdlttpbjp'; // Remplacez par votre mot de passe SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('mercierelouen@gmail.com', 'Fuecoco');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Email Confirmation';
                $mail->Body    = 'Please confirm your email by clicking the link: <a href="http://localhost/projet_fuecoco/index.php?action=confirm_email&token=' . $confirmation_token . '">Confirm Email</a>';
                $mail->AltBody = 'Please confirm your email by clicking the link: http://localhost/projet_fuecoco/index.php?action=confirm_email&token=' . $confirmation_token;

                $mail->send();
                echo "Registration successful! Please check your email to confirm your account.";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } catch (\PDOException $e) {
            echo "Erreur lors de la création de l'utilisateur : " . $e->getMessage();
        }
    }

    public function confirmUser($token) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE confirmation_token = :confirmation_token");
            $stmt->bindParam(':confirmation_token', $token);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                $stmt = $this->db->prepare("UPDATE users SET is_confirmed = 1, confirmation_token = NULL WHERE confirmation_token = :confirmation_token");
                $stmt->bindParam(':confirmation_token', $token);
                $stmt->execute();
                return true;
            } else {
                return false;
            }
        } catch (\PDOException $e) {
            echo "Erreur lors de la confirmation de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }
}
