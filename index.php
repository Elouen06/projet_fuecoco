<?php
session_start();

const CSS = 'Assets/css/';
const JS = 'Assets/js/';
const TMP = 'Assets/templates/';

require_once TMP . 'top.php';
require_once TMP . 'menu.php';
?>

<main>
    <!-- Carousel -->
    <div id="carousel" class="carousel">
        <!-- Ajouter vos images ici -->
        <div class="carousel-item"><img src="Assets/images/logement1.jpg" alt="Logement 1"></div>
        <div class="carousel-item"><img src="Assets/images/logement2.jpg" alt="Logement 2"></div>
    </div>

    <!-- Description des logements -->
    <section id="description">
        <h2>inserer titre description</h2>
        <p>inserer description</p>
    </section>

    <!-- Calendrier de réservation -->
    <div id="reservation">
        <h2>Réservez votre séjour</h2>
        <form>
            <label for="start-date">Date de début :</label>
            <input type="date" id="start-date" name="start-date" required>
            
            <label for="end-date">Date de fin :</label>
            <input type="date" id="end-date" name="end-date" required>
            
            <label for="num-guests">Nombre de voyageurs :</label>
            <input type="number" id="num-guests" name="num-guests" value="1" readonly required>
            
            <button type="submit">Réserver</button>
        </form>
        
        <h2>Calendrier des réservations</h2>
        <div class="calendar-navigation">
            <button id="prev-month">&lt;</button>
            <span id="current-month"></span>
            <button id="next-month">&gt;</button>
        </div>
        <div class="calendar" id="calendar">
            <?php
            function generateCalendar($month, $year) {
                // Jours de la semaine en français
                $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                
                // Nombre de jours dans le mois
                $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                
                // Premier jour du mois (0 pour lundi, 6 pour dimanche)
                $firstDayOfMonth = (date('N', strtotime("$year-$month-01")) - 1) % 7;

                echo '<table>';
                echo '<thead><tr>';
                foreach ($daysOfWeek as $day) {
                    echo "<th>$day</th>";
                }
                echo '</tr></thead>';
                echo '<tbody>';
                echo '<tr>';

                // Affichez les cellules vides avant le premier jour du mois
                for ($i = 0; $i < $firstDayOfMonth; $i++) {
                    echo '<td></td>';
                }

                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $class = 'available';
                    $date = "$year-$month-".str_pad($day, 2, '0', STR_PAD_LEFT);
                    echo "<td class='$class' id='day-$date' data-date='$date'>$day</td>";

                    // Si le jour est dimanche, commencez une nouvelle ligne
                    if (($day + $firstDayOfMonth) % 7 == 0) {
                        echo '</tr><tr>';
                    }
                }

                // Affichez les cellules vides après le dernier jour du mois
                while (($day + $firstDayOfMonth) % 7 != 1) {
                    echo '<td></td>';
                    $day++;
                }

                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
            }

            // Utilisation de la fonction pour générer le calendrier du mois en cours
            $month = date('m');
            $year = date('Y');
            generateCalendar($month, $year);
            ?>
        </div>
    </div>

    <!-- Map -->
    <div id="map">
        <!-- Carte interactive -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509282!2d144.96305791531645!3d-37.81362797975192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf0727e4f4e6b8ad!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1614213499570!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</main>

<script src="<?= JS; ?>calendar.js"></script>

<?php
require_once TMP . 'bottom.php';
?>
