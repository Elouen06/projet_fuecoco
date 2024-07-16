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
        $gbd = $this->db->query("SELECT date FROM blocked_dates");
        return $gbd->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addBlockedDates($blockedDates) {
        $this->db->beginTransaction();
        $ubd = $this->db->prepare("INSERT INTO blocked_dates (date) VALUES (:date) ON DUPLICATE KEY UPDATE date = :date");
        foreach ($blockedDates as $date) {
            $ubd->execute(['date' => $date]);
        }
        $this->db->commit();
    }

    public function deleteUnblockedDates($unblockedDates) {
        $this->db->beginTransaction();
        $dbd = $this->db->prepare("DELETE FROM blocked_dates WHERE date = :date");
        foreach ($unblockedDates as $date) {
            $dbd->execute(['date' => $date]);
        }
        $this->db->commit();
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
