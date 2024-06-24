<?php

namespace Controllers;

use Models\HomeModel;
use Views\HomeView;

class HomeController
{
    public function index()
    {
        $homeModel = new HomeModel();
        $month = date('m');
        $year = date('Y');
        $calendar = $homeModel->generateCalendar($month, $year);
        
        $homeView = new HomeView();
        $homeView->render($calendar);
    }
}
?>
