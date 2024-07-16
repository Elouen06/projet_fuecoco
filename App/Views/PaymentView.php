<?php
namespace Views;

class PaymentView {
    public function render() {
        // Générer le token CSRF
        $csrfToken = generate_csrf_token();

        // Afficher le message d'erreur s'il y en a un
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
            unset($_SESSION['error_message']);
        }

        // Afficher le formulaire de choix de paiement
        echo '<main>
            <h2>Choix du paiement</h2>
            <form action="reservation/processus_du_paiment" method="post" id="payment-choice-form">
                <label for="payment-choice">Choisir un type de paiement :</label>
                <select id="payment-choice" name="payment_choice">
                    <option value="one_time">Paiement en une seule fois</option>
                    <option value="three_times">Paiement en 3 fois</option>
                </select>
                <br>
                
                <label for="payment-method">Choisir une méthode de paiement :</label>
                <select id="payment-method" name="payment_method">
                    <option value="credit_card">Carte bancaire</option>
                    <option value="paypal">PayPal</option>
                    <!-- Ajouter d\'autres méthodes de paiement ici -->
                </select>
                <br>
                
                <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                <button type="submit" name="submit_payment">Passer au paiement</button>
            </form>
        </main>';
    }
}
?>
