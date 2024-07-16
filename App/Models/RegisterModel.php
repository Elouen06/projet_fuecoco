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

        if ($this->emailExists($email)) {
            throw new \Exception("Email already exists.");
        }

        if ($this->usernameExists($username)) {
            throw new \Exception("Username already exists.");
        }

        // Hash le mot de passe avant de le stocker
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $confirmation_token = bin2hex(random_bytes(32));

        try {
            $cu = $this->db->prepare("INSERT INTO users (username, email, pw, confirmation_token) VALUES (:username, :email, :pw, :confirmation_token)");
            $cu->bindParam(':username', $username);
            $cu->bindParam(':email', $email);
            $cu->bindParam(':pw', $hashedPassword);
            $cu->bindParam(':confirmation_token', $confirmation_token);
            $cu->execute();

            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Serveur SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'mercierelouen@gmail.com'; // Adresse email SMTP
                $mail->Password = 'ktodmftjdlttpbjp'; // Mot de passe SMTP
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

    public function emailExists($email) {
        $ee = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $ee->bindParam(':email', $email);
        $ee->execute();
        return $ee->fetchColumn() > 0;
    }

    public function usernameExists($username) {
        $ue = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $ue->bindParam(':username', $username);
        $ue->execute();
        return $ue->fetchColumn() > 0;
    }
}
?>
