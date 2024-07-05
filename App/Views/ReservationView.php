<?php
namespace Views;

class ReservationView {
    public function render($reservation) {
        // Générer le token CSRF
        $csrfToken = generate_csrf_token();

        $basePrice = $reservation['total_price']; // Prix de base de la réservation
        $deposit = 500; // Caution fixe de 500€
        $totalPriceWithDeposit = $basePrice + $deposit; // Prix total incluant la caution

        echo '<main>
            <h2>Récapitulatif de la réservation</h2>
            <form action="index.php?action=confirm_reservation" method="post" id="reservation-form">
                <label for="start-date">Date de début :</label>
                <input type="date" id="start-date" name="start_date" value="' . htmlspecialchars($reservation['start_date']) . '">
                <br>
                
                <label for="end-date">Date de fin :</label>
                <input type="date" id="end-date" name="end_date" value="' . htmlspecialchars($reservation['end_date']) . '">
                <br>
                
                <label for="num-guests">Nombre de voyageurs :</label>
                <input type="number" id="num-guests" name="num_guests" value="' . htmlspecialchars($reservation['num_guests']) . '">
                <br>
                
                <label for="base-price">Prix de la réservation :</label>
                <input type="text" id="base-price" name="base_price" value="' . htmlspecialchars($basePrice) . '€" readonly>
                <br>
                
                <label for="deposit">Caution :</label>
                <input type="text" id="deposit" name="deposit" value="' . $deposit . '€" readonly>
                <br>
                
                <label for="total-price">Prix total avec caution :</label>
                <input type="text" id="total-price" name="total_price" value="' . htmlspecialchars($totalPriceWithDeposit) . '€" readonly>
                <br>
                
                <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                <button type="submit">Passer au paiement</button>
            </form>
        </main>';
    }
}
?>
