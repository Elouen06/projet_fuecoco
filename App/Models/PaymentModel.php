<?php
namespace Models;

use App\Database;

class PaymentModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function updateReservationStatus($reservationId, $status) {
        $ur = $this->db->prepare("UPDATE reservations SET status = :status WHERE id = :reservation_id");
        $ur->execute([
            ':status' => $status,
            ':reservation_id' => $reservationId
        ]);
    }

}
?>
