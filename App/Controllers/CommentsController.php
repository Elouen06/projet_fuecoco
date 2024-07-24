<?php
namespace Controllers;

use Models\CommentsModel;
use Views\HomeView;

class CommentsController {
    protected $commentsModel;

    public function __construct() {
        $this->commentsModel = new CommentsModel();
    }

    public function addComment() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            $userId = $_SESSION['id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];

            if ($this->commentsModel->addComment($userId, $rating, $comment)) {
                echo "Comment added successfully"; // Ajoutez cette ligne pour vérifier l'ajout
            } else {
                echo "Failed to add comment"; // Ajoutez cette ligne pour vérifier l'échec
            }

            header('Location: accueil');
        } else {
            die("Invalid CSRF token.");
        }
    }

    public function showComments() {
        return $this->commentsModel->getComments();
    }
}
?>
