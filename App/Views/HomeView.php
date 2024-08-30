<?php
namespace Views;

class HomeView {
    public function render($calendar, $blockedDates, $reservedDates, $comments) {
        $csrfToken = generate_csrf_token();
        echo '<main>
            <div id="blockimages" class="blockimages">
                <div class="blockimages-main"><img src="Assets/images/imgtest1.jpeg" alt="Logement 1" id="mainImage"></div>
                <div class="blockimages-thumbnails">
                    <div class="blockimages-item"><img src="Assets/images/imgtest1.jpeg" alt="Logement 1" onclick="changeImage(this)"></div>
                    <div class="blockimages-item"><img src="Assets/images/imgtest3.png" alt="Logement 3" onclick="changeImage(this)"></div>
                    <div class="blockimages-item"><img src="Assets/images/imgtest1.jpeg" alt="Logement 5" onclick="changeImage(this)"></div>
                    <div class="blockimages-item"><img src="Assets/images/imgtest3.png" alt="Logement 6" onclick="changeImage(this)"></div>
                </div>
            </div>

            <section class="main-content">
                <div id="description" class="description-section">
                    <h2>Insérer Titre Description</h2>
                    <p>Insérer Description</p>
                </div>

                <div class="reservation-section">
                    <!-- Formulaire de réservation -->
                    <div class="calendar-block">
                        <h2>Réservez votre séjour</h2>
                        <form action="?action=reserve" method="post">
                            <label for="start-date">Date de début :</label>
                            <input type="date" id="start-date" name="start-date" required>
                            
                            <label for="end-date">Date de fin :</label>
                            <input type="date" id="end-date" name="end-date" required>
                            
                            <label for="num-guests">Nombre de voyageurs :</label>
                            <input type="number" id="num-guests" name="num-guests" value="1" min="1" max="4" required>
                            
                            <label for="total-price">Prix total :</label>
                            <input type="text" id="total-price" name="total-price" readonly required>

                            <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                            
                            <button type="submit" id="reserve-button">Réserver</button>
                        </form>
                    </div>

                    <!-- Calendrier de réservation -->
                    <div class="calendar-block">
                        <div class="calendar-navigation">
                            <button id="prev-month">&lt;</button>
                            <span id="current-month"></span>
                            <button id="next-month">&gt;</button>
                        </div>
                        <div class="calendar" id="calendar">
                            ' . $calendar . '
                        </div>
                    </div>
                </div>
            </section>

            <!-- Commentaires -->
            <section id="comments">
                <h2>Commentaires</h2>
                <form method="post" action="index.php?action=add_comment">
                    <div class="comment-rating">
                        <label for="rating">Note :</label>
                        <input type="number" id="rating" name="rating" min="1" max="5" required>
                        <div class="stars">
                            <i class="star">&#9733;</i>
                            <i class="star">&#9733;</i>
                            <i class="star">&#9733;</i>
                            <i class="star">&#9733;</i>
                            <i class="star">&#9733;</i>
                        </div>
                    </div>
                    
                    <label for="comment">Commentaire :</label>
                    <textarea id="comment" name="comment" required></textarea>

                    <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">

                    <button type="submit">Ajouter un commentaire</button>
                </form>
                <h3>Commentaires des utilisateurs</h3>';
                foreach ($comments as $comment) {
                    echo '<div class="comment">
                        <p><strong>' . htmlspecialchars($comment['username']) . ':</strong> ' . htmlspecialchars($comment['comment']) . '</p>
                        <p>Note: ' . htmlspecialchars($comment['rating']) . '/5</p>
                    </div>';
                }
                echo '</section>

                <!-- Map -->
            <div id="map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509282!2d144.96305791531645!3d-37.81362797975192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf0727e4f4e6b8ad!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1614213499570!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
            
        </main>
        <script>
            const blockedDates = ' . json_encode($blockedDates) . ';
            const reservedDates = ' . json_encode($reservedDates) . ';

            function changeImage(element) {
                var mainImage = document.getElementById("mainImage");
                mainImage.src = element.src;
                mainImage.alt = element.alt;
            }
        </script>
        <script src="Assets/js/calendar.js"></script>';
    }
}
?>
