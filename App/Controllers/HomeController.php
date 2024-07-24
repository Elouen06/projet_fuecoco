<?php
namespace Controllers;

use Models\HomeModel;
use Models\ReservationModel;
use Views\HomeView;
use Controllers\CommentsController;

class HomeController {
    public function index() {
        $homeModel = new HomeModel();
        $reservationModel = new ReservationModel();
        $commentsController = new CommentsController();

        $month = date('m');
        $year = date('Y');
        $calendar = $homeModel->generateCalendar($month, $year);

        $blockedDates = array_column($homeModel->getBlockedDates(), 'date');
        $reservedDates = $reservationModel->getReservedDates();
        $comments = $commentsController->showComments();

        $homeView = new HomeView();
        $homeView->render($calendar, $blockedDates, $reservedDates, $comments);
    }
}
?>
