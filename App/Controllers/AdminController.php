<?php
namespace Controllers;

use Models\AdminModel;
use Models\ReservationModel;
use Views\AdminView;

class AdminController {
    protected $adminModel;
    protected $reservationModel;

    public function __construct() {
        $this->adminModel = new AdminModel();
        $this->reservationModel = new ReservationModel();
    }

    public function dashboard() {
        $blockedDates = array_column($this->adminModel->getBlockedDates(), 'date');
        $reservedDates = $this->adminModel->getReservedDates();
        $reservations = $this->reservationModel->getAllReservations();

        $adminView = new AdminView();
        $adminView->render('', $blockedDates, $reservedDates, $reservations);
    }

    public function blockDates() {
        if (isset($_POST['csrf_token']) && validate_csrf_token($_POST['csrf_token'])) {
            if (isset($_POST['blocked_dates'])) {
                $blockedDates = json_decode($_POST['blocked_dates'], true);
                $this->adminModel->updateBlockedDates($blockedDates);
            }
            header('Location: ?action=admin');
        } else {
            die("Invalid CSRF token.");
        }
    }

    public function cancelReservation($reservationId) {
        $this->reservationModel->deleteReservation($reservationId);
        header('Location: ?action=admin');
    }
}
