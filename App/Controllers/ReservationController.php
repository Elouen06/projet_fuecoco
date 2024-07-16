<?php
namespace Controllers;

use Models\ReservationModel;
use Views\ReservationView;

class ReservationController
{
    protected $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new ReservationModel();
    }

    public function reserve()
    {
        if (!isset($_SESSION['id'])) {
            header('Location: connexion');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['id'];
            $startDate = $_POST['start-date'];
            $endDate = $_POST['end-date'];
            $numGuests = $_POST['num-guests'];
            $totalPrice = 100 * (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) * $numGuests;

            $reservationId = $this->reservationModel->createReservation($userId, $startDate, $endDate, $numGuests, $totalPrice);
            $_SESSION['reservation_id'] = $reservationId;
            header('Location: reservation/résumé');
            exit;
        }
    }

    public function reservationSummary()
    {
        if (!isset($_SESSION['reservation_id'])) {
            header('Location: accueil');
            exit;
        }

        $reservation = $this->reservationModel->getReservationById($_SESSION['reservation_id']);
        $reservationView = new ReservationView();
        $reservationView->render($reservation);
    }

    public function confirmReservation()
    {
        if (!isset($_SESSION['reservation_id'])) {
            header('Location: reservation/résumé');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $startDate = $_POST['start_date'];
            $endDate = $_POST['end_date'];
            $numGuests = $_POST['num_guests'];
            $basePrice = 100 * (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) * $numGuests;

            // Ajouter la caution de 500€
            $totalPriceWithDeposit = $basePrice + 500;

            $this->reservationModel->updateReservation($_SESSION['reservation_id'], $startDate, $endDate, $numGuests, $totalPriceWithDeposit);

            header('Location: choix_du_paiment');
            exit;
        }
    }

    public function userReservations() {
        if (!isset($_SESSION['id'])) {
            header('Location: connexion');
            exit;
        }

        $userId = $_SESSION['id'];
        $reservations = $this->reservationModel->getUserReservations($userId);

        $reservationView = new ReservationView();
        $reservationView->renderUserReservations($reservations);
    }

    public function cancelUserReservation($reservationId) {
        $this->reservationModel->deleteReservation($reservationId);
        header('Location: mes_reservation');
    }
}
