<?php
require 'vendor/autoload.php';

use Models\ReservationModel;

$cleanupReservations = function() {
    $reservationModel = new ReservationModel();
    $reservations = $reservationModel->getUnconfirmedReservationsOlderThan(15); // 15 minutes

    foreach ($reservations as $reservation) {
        $reservationModel->deleteReservation($reservation['id']);
    }
};

$cleanupReservations();
?>
