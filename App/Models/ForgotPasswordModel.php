<?php
namespace Models;

use App\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function sendResetLink($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            $reset_token = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare("UPDATE users SET reset_token = :reset_token WHERE email = :email");
            $stmt->bindParam(':reset_token', $reset_token);
            $stmt->bindParam(':email', $email);
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
                $mail->Subject = 'Password Reset';
                $mail->Body    = 'Here is your reset link: <a href="http://localhost/projet_fuecoco/index.php?action=reset_password&token=' . $reset_token . '">Reset Password</a>';
                $mail->AltBody = 'Here is your reset link: http://localhost/projet_fuecoco/index.php?action=reset_password&token=' . $reset_token;

                $mail->send();
                return true;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                return false;
            }
        } else {
            return false;
        }
    }

    public function validateResetToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :reset_token");
        $stmt->bindParam(':reset_token', $token);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updatePassword($token, $new_password) {
        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET pw = :pw, reset_token = NULL WHERE reset_token = :reset_token");
        $stmt->bindParam(':pw', $hashedPassword);
        $stmt->bindParam(':reset_token', $token);
        return $stmt->execute();
    }
}


