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
        $this->cleanUpExpiredReservations();
    }

    private function cleanUpExpiredReservations()
    {
        $minutes = 15;
        $oldReservations = $this->reservationModel->getUnconfirmedReservationsOlderThan($minutes);
        foreach ($oldReservations as $reservation) {
            $this->reservationModel->deleteReservation($reservation['id']);
        }
    }

    private function isReservationValid($reservationId)
    {
        $reservation = $this->reservationModel->getReservationById($reservationId);
        return $reservation && !$reservation['confirmed'] && (strtotime($reservation['created_at']) >= strtotime('-15 minutes'));
    }

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

            // Vérification des dates disponibles
            $reservedDates = $this->reservationModel->getReservedDates();
            $blockedDates = $this->reservationModel->getBlockedDates();
            $selectedDates = $this->generateDateRange($startDate, $endDate);

            foreach ($selectedDates as $date) {
                if (in_array($date, $reservedDates) || in_array($date, $blockedDates)) {
                    $_SESSION['error_message'] = "Les dates sélectionnées ne sont pas disponibles.";
                    header('Location: index.php?action=reservation');
                    exit;
                }
            }

            $totalPrice = 100 * (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) * $numGuests;

            $reservationId = $this->reservationModel->createReservation($userId, $startDate, $endDate, $numGuests, $totalPrice);
            $_SESSION['reservation_id'] = $reservationId;
            header('Location: index.php?action=reservation_summary');
            exit;
        }
    }

    private function generateDateRange($startDate, $endDate) {
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }
        return $dates;
    }

    public function reservationSummary()
    {
        session_start();
        if (!isset($_SESSION['reservation_id']) || !$this->isReservationValid($_SESSION['reservation_id'])) {
            header('Location: index.php');
            exit;
        }

        $reservation = $this->reservationModel->getReservationById($_SESSION['reservation_id']);
        $reservationView = new ReservationView();
        $reservationView->render($reservation);
    }

    public function confirmReservation()
    {
        session_start();
        if (!isset($_SESSION['reservation_id']) || !$this->isReservationValid($_SESSION['reservation_id'])) {
            header('Location: index.php?action=reservation_summary');
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

            header('Location: index.php?action=payment_choice');
            exit;
        }
    }

    public function userReservations()
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $reservations = $this->reservationModel->getUserReservations($_SESSION['id']);
        $reservationView = new ReservationView();
        $reservationView->userReservations($reservations);
    }

    public function cancelUserReservation($reservationId)
    {
        session_start();
        if (!isset($_SESSION['id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->reservationModel->cancelReservation($reservationId);
        // Envoyer un mail à l'administrateur
        $this->sendCancellationEmailToAdmin($reservationId);
        $_SESSION['message'] = "Votre réservation a été annulée avec succès.";
        header('Location: index.php?action=user_reservations');
    }

    private function sendCancellationEmailToAdmin($reservationId)
    {
        // Code pour envoyer un mail à l'administrateur
    }
}
