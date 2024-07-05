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
            $totalPrice = 100 * (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24) * $numGuests;

            $reservationId = $this->reservationModel->createReservation($userId, $startDate, $endDate, $numGuests, $totalPrice);
            $_SESSION['reservation_id'] = $reservationId;
            header('Location: index.php?action=reservation_summary');
            exit;
        }
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

            // Assurez-vous que reservation_id est toujours défini
            error_log('Reservation ID in session before redirect to payment_choice: ' . $_SESSION['reservation_id']);

            header('Location: index.php?action=payment_choice');
            exit;
        }
    }
}
?>
