<?php
namespace Models;

use App\Database;

class AdminModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getBlockedDates() {
        $stmt = $this->db->query("SELECT date FROM blocked_dates");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function updateBlockedDates($blockedDates) {
        $this->db->beginTransaction();
        $stmt = $this->db->prepare("INSERT INTO blocked_dates (date) VALUES (:date) ON DUPLICATE KEY UPDATE date = :date");
        foreach ($blockedDates as $date) {
            $stmt->execute(['date' => $date]);
        }
        $this->db->commit();
    }

    public function getReservedDates() {
        $stmt = $this->db->query("SELECT start_date, end_date FROM reservations WHERE status != 'Cancelled'");
        $reservations = $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
