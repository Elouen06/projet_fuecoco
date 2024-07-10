<?php
namespace Controllers;

use Models\HomeModel;
use Models\ReservationModel;
use Views\HomeView;

class HomeController
{
    public function index()
    {
        // Clean up unconfirmed reservations
        $this->cleanUpUnconfirmedReservations();

        $homeModel = new HomeModel();
        $reservationModel = new ReservationModel();
        
        $month = date('m');
        $year = date('Y');
        
        $calendar = $homeModel->generateCalendar($month, $year);
        $reservedDates = $reservationModel->getReservedDates();
        $blockedDates = array_column($reservationModel->getBlockedDates(), 'date');
        
        $homeView = new HomeView();
        $homeView->render($calendar, $reservedDates, $blockedDates);
    }

    private function cleanUpUnconfirmedReservations()
    {
        $reservationModel = new ReservationModel();
        $reservations = $reservationModel->getUnconfirmedReservationsOlderThan(15); // 15 minutes

        foreach ($reservations as $reservation) {
            $reservationModel->deleteReservation($reservation['id']);
        }
    }
}
