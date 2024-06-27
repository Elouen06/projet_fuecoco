<?php
namespace Controllers;

use Models\ReservationModel;
use Views\ReservationView;

class ReservationController
{
    public function reserve()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id'];
            $startDate = $_POST['start-date'];
            $endDate = $_POST['end-date'];
            $numGuests = $_POST['num-guests'];
            $totalPrice = 100 * (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) * $numGuests;

            $reservationModel = new ReservationModel();
            $reservationId = $reservationModel->createReservation($userId, $startDate, $endDate, $numGuests, $totalPrice);
            $_SESSION['reservation_id'] = $reservationId;
            header('Location: index.php?action=reservation_summary');
            exit;
        }
    }

    public function reservationSummary()
    {
        session_start();
        if (!isset($_SESSION['reservation_id'])) {
            header('Location: index.php');
            exit;
        }

        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->getReservationById($_SESSION['reservation_id']);

        $reservationView = new ReservationView();
        $reservationView->render($reservation);
    }

    public function confirmReservation()
    {
        session_start();
        if (!isset($_SESSION['reservation_id'])) {
            header('Location: index.php');
            exit;
        }

        $reservationModel = new ReservationModel();
        $reservationModel->updateReservationWithDeposit($_SESSION['reservation_id']);
        // Mark the reservation as confirmed
        $stmt = $reservationModel->db->prepare("UPDATE reservations SET confirmed = 1 WHERE id = :reservation_id");
        $stmt->bindParam(':reservation_id', $_SESSION['reservation_id']);
        $stmt->execute();

        unset($_SESSION['reservation_id']);
        header('Location: index.php?action=reservation_complete');
        exit;
    }
}
