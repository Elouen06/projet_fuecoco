<?php
namespace Models;

use App\Database;

class ReservationModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function createReservation($userId, $startDate, $endDate, $numGuests, $totalPrice) {
        $stmt = $this->db->prepare("INSERT INTO reservations (user_id, start_date, end_date, num_guests, total_price, created_at) VALUES (:user_id, :start_date, :end_date, :num_guests, :total_price, NOW())");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':num_guests', $numGuests);
        $stmt->bindParam(':total_price', $totalPrice);
        $stmt->execute();
        
        return $this->db->lastInsertId();
    }

    public function getReservationById($reservationId) {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateReservationWithDeposit($reservationId) {
        $stmt = $this->db->prepare("UPDATE reservations SET total_price = total_price + 500 WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();
    }

    public function deleteReservation($reservationId) {
        $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();
    }

    public function getUnconfirmedReservationsOlderThan($minutes) {
        $stmt = $this->db->prepare("SELECT * FROM reservations WHERE created_at < NOW() - INTERVAL :minutes MINUTE AND confirmed = 0");
        $stmt->bindParam(':minutes', $minutes, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function updateReservation($reservationId, $startDate, $endDate, $numGuests, $totalPrice) {
        $stmt = $this->db->prepare("UPDATE reservations SET start_date = :start_date, end_date = :end_date, num_guests = :num_guests, total_price = :total_price, confirmed = 1 WHERE id = :reservation_id");
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':num_guests', $numGuests);
        $stmt->bindParam(':total_price', $totalPrice);
        $stmt->bindParam(':reservation_id', $reservationId);
        $stmt->execute();
    }
}