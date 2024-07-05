<?php
namespace Controllers;

use Views\PaymentView;
use Models\PaymentModel;

class PaymentController {
    public function paymentChoice() {
        $view = new PaymentView();
        $view->render();
    }

    public function processPayment() {
        // Vérifier le token CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = "Erreur de sécurité. Veuillez réessayer.";
            header("Location: index.php?action=payment_choice");
            exit;
        }

        // Vérifier si le bouton de paiement a été soumis
        if (!isset($_POST['submit_payment'])) {
            $_SESSION['error_message'] = "Action non autorisée.";
            header("Location: index.php?action=payment_choice");
            exit;
        }

        // Récupérer les données du formulaire
        $paymentMethod = $_POST['payment_method'];
        $paymentChoice = $_POST['payment_choice']; // Récupérer le choix de paiement
        $reservationId = $_SESSION['reservation_id']; // Assurez-vous de récupérer correctement l'id de la réservation

        // Simulation de la réussite ou de l'échec du paiement
        $paymentSuccessful = (rand(0, 1) == 1); // Simuler une chance de réussite de 50%

        if ($paymentSuccessful) {
            // Mettre à jour le statut de la réservation en "paid"
            $status = 'paid';

            $paymentModel = new PaymentModel();
            $paymentModel->updateReservationStatus($reservationId, $status);

            // Redirection vers une page de confirmation ou autre
            header("Location: index.php?action=payment_success");
            exit;
        } else {
            $_SESSION['error_message'] = "Le paiement a échoué. Veuillez réessayer.";
            header("Location: index.php?action=payment_choice");
            exit;
        }
    }
}
?>
