<?php
namespace Models;

use App\Database;

class HomeModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function generateCalendar($month, $year) {
        $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $firstDayOfMonth = (date('N', strtotime("$year-$month-01")) - 1) % 7;

        ob_start(); // Start output buffering

        echo '<table>';
        echo '<thead><tr>';
        foreach ($daysOfWeek as $day) {
            echo "<th>$day</th>";
        }
        echo '</tr></thead>';
        echo '<tbody>';
        echo '<tr>';

        for ($i = 0; $i < $firstDayOfMonth; $i++) {
            echo '<td></td>';
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $class = 'available';
            $date = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
            echo "<td class='$class' id='day-$date' data-date='$date'>$day</td>";

            if (($day + $firstDayOfMonth) % 7 == 0) {
                echo '</tr><tr>';
            }
        }

        while (($day + $firstDayOfMonth) % 7 != 1) {
            echo '<td></td>';
            $day++;
        }

        echo '</tr>';
        echo '</tbody>';
        echo '</table>';

        return ob_get_clean(); // Return the contents of the output buffer
    }

    public function getBlockedDates() {
        $gbd = $this->db->query("SELECT date FROM blocked_dates");
        return $gbd->fetchAll(\PDO::FETCH_ASSOC);
    }
}
