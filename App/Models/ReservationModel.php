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
        $cr = $this->db->prepare("INSERT INTO reservations (user_id, start_date, end_date, num_guests, total_price, created_at) VALUES (:user_id, :start_date, :end_date, :num_guests, :total_price, NOW())");
        $cr->bindParam(':user_id', $userId);
        $cr->bindParam(':start_date', $startDate);
        $cr->bindParam(':end_date', $endDate);
        $cr->bindParam(':num_guests', $numGuests);
        $cr->bindParam(':total_price', $totalPrice);
        $cr->execute();
        
        return $this->db->lastInsertId();
    }

    public function getReservationById($reservationId) {
        $grbi = $this->db->prepare("SELECT * FROM reservations WHERE id = :reservation_id");
        $grbi->bindParam(':reservation_id', $reservationId);
        $grbi->execute();
        return $grbi->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateReservationWithDeposit($reservationId) {
        $urwd = $this->db->prepare("UPDATE reservations SET total_price = total_price + 500 WHERE id = :reservation_id");
        $urwd->bindParam(':reservation_id', $reservationId);
        $urwd->execute();
    }

    public function deleteReservation($reservationId) {
        $dr = $this->db->prepare("DELETE FROM reservations WHERE id = :reservation_id");
        $dr->bindParam(':reservation_id', $reservationId);
        $dr->execute();
    }

    public function getUnconfirmedReservationsOlderThan($minutes) {
        $gurot = $this->db->prepare("SELECT * FROM reservations WHERE created_at < NOW() - INTERVAL :minutes MINUTE AND confirmed = 0");
        $gurot->bindParam(':minutes', $minutes, \PDO::PARAM_INT);
        $gurot->execute();
        return $gurot->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateReservation($reservationId, $startDate, $endDate, $numGuests, $totalPrice) {
        $ur = $this->db->prepare("UPDATE reservations SET start_date = :start_date, end_date = :end_date, num_guests = :num_guests, total_price = :total_price, confirmed = 1 WHERE id = :reservation_id");
        $ur->bindParam(':start_date', $startDate);
        $ur->bindParam(':end_date', $endDate);
        $ur->bindParam(':num_guests', $numGuests);
        $ur->bindParam(':total_price', $totalPrice);
        $ur->bindParam(':reservation_id', $reservationId);
        $ur->execute();
    }

    public function getUserReservations($userId) {
        $gur = $this->db->prepare("SELECT * FROM reservations WHERE user_id = :user_id");
        $gur->bindParam(':user_id', $userId);
        $gur->execute();
        return $gur->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllReservations() {
        $gar = $this->db->query("SELECT * FROM reservations");
        return $gar->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getBlockedDates() {
        $gbd = $this->db->query("SELECT date FROM blocked_dates");
        return $gbd->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getReservedDates() {
        $grd = $this->db->query("SELECT start_date, end_date FROM reservations WHERE status != 'Cancelled'");
        $reservations = $grd->fetchAll(\PDO::FETCH_ASSOC);
        $reservedDates = [];
        foreach ($reservations as $reservation) {
            $period = new \DatePeriod(
                new \DateTime($reservation['start_date']),
                new \DateInterval('P1D'),
                (new \DateTime($reservation['end_date']))->modify('+1 day')
            );
            foreach ($period as $date) {
                $reservedDates[] = $date->format('Y-m-d');
            }
        }
        return $reservedDates;
    }
}
