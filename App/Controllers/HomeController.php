<?php
namespace Controllers;

use Models\HomeModel;
use Models\ReservationModel;
use Views\HomeView;

class HomeController {
    public function index() {
        $homeModel = new HomeModel();
        $reservationModel = new ReservationModel();

        $month = date('m');
        $year = date('Y');
        $calendar = $homeModel->generateCalendar($month, $year);

        $blockedDates = array_column($homeModel->getBlockedDates(), 'date');
        $reservedDates = $reservationModel->getReservedDates();

        $homeView = new HomeView();
        $homeView->render($calendar, $blockedDates, $reservedDates);
    }
}
