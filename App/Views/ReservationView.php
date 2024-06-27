<?php
namespace Views;

class ReservationView
{
    public function render($reservation)
    {
        // Générer le token CSRF
        $csrfToken = generate_csrf_token();

        echo '<main>
            <h2>Récapitulatif de la réservation</h2>
            <p>Date de début : ' . htmlspecialchars($reservation['start_date']) . '</p>
            <p>Date de fin : ' . htmlspecialchars($reservation['end_date']) . '</p>
            <p>Nombre de voyageurs : ' . htmlspecialchars($reservation['num_guests']) . '</p>
            <p>Prix total : ' . htmlspecialchars($reservation['total_price']) . '€</p>
            <form action="?action=confirm_reservation" method="post">
                <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                <button type="submit">Passer au paiement</button>
            </form>
        </main>';
    }
}


