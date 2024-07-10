<div class="logo">inserer logo ici</div>
<nav>
    <?php
    echo '<a href="index.php">Home</a>';
    if (isset($_SESSION['user'])) {
        echo '<a href="?action=login">Profil</a>';
        echo '<a href="?action=user_reservations">Mes réservations</a>';
        
        // Vérifiez si l'utilisateur a un niveau d'accès d'administrateur (id_level = 2)
        if (isset($_SESSION['id_level']) && $_SESSION['id_level'] == 2) {
            echo '<a href="?action=admin">Admin</a>';
        }

        // Générer le jeton CSRF pour le formulaire de déconnexion
        $csrfToken = generate_csrf_token();

        echo '<form method="POST" action="?action=logout" style="display:inline;">
                <input type="hidden" name="csrf_token" value="' . $csrfToken . '">
                <button type="submit">Déconnexion</button>
              </form>';
    } else {
        echo '<a href="?action=inscription">Inscription</a>';
        echo '<a href="?action=login">Connexion</a>';
    }
    ?>
</nav>
