<?php
namespace Views;

class HomeView {
    public function render($calendar, $blockedDates, $reservedDates) {
        // Générer le token CSRF
        $csrfToken = generate_csrf_token();
        echo '<main>
            <!-- Carousel -->
            <div id="carousel" class="carousel">
                <div class="carousel-item"><img src="Assets/images/logement1.jpg" alt="Logement 1"></div>
                <div class="carousel-item"><img src="Assets/images/logement2.jpg" alt="Logement 2"></div>
            </div>

            <!-- Description des logements -->
            <section id="description">
                <h2>Insérer Titre Description</h2>
                <p>Insérer Description</p>
            </section>

            <!-- Calendrier de réservation -->
            <div class="calendar-block">
                <h2>Réservez votre séjour</h2>
                <form action="reservation" method="post">
                    <label for="start-date">Date de début :</label>
                    <input type="date" id="start-date" name="start-date" required>
                    
                    <label for="end-date">Date de fin :</label>
                    <input type="date" id="end-date" name="end-date" required>
                    
                    <label for="num-guests">Nombre de voyageurs :</label>
                    <input type="number" id="num-guests" name="num-guests" value="1" min="1" max="4" required>
                    
                    <label for="total-price">Prix total :</label>
                    <input type="text" id="total-price" name="total-price" readonly required>

                    <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                    
                    <button type="submit">Réserver</button>
                </form>
                
                <h2>Calendrier des réservations</h2>
                <div class="calendar-navigation">
                    <button id="prev-month">&lt;</button>
                    <span id="current-month"></span>
                    <button id="next-month">&gt;</button>
                </div>
                <div class="calendar" id="calendar">
                    ' . $calendar . '
                </div>
            </div>

            <!-- Map -->
            <div id="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509282!2d144.96305791531645!3d-37.81362797975192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf0727e4f4e6b8ad!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1614213499570!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </main>
        <script>
            const blockedDates = ' . json_encode($blockedDates) . ';
            const reservedDates = ' . json_encode($reservedDates) . ';
        </script>
        <script src="Assets/js/calendar.js"></script>';
    }
}
