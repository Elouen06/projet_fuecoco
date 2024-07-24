<?php
namespace Models;

use App\Database;

class CommentsModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function addComment($userId, $rating, $comment) {
        try {
            $stmt = $this->db->prepare("INSERT INTO comments (user_id, rating, comment) VALUES (:user_id, :rating, :comment)");
            $stmt->execute(['user_id' => $userId, 'rating' => $rating, 'comment' => $comment]);
            return true; // Retourne true si l'insertion a réussi
        } catch (\PDOException $e) {
            echo "Error: " . $e->getMessage(); // Ajoutez cette ligne pour afficher les erreurs SQL
            return false; // Retourne false si l'insertion a échoué
        }
    }

    public function getComments() {
        $stmt = $this->db->query("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id ORDER BY c.created_at DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>
