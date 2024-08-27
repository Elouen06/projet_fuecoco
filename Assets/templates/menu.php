<header>
    <div class="logo">inserer logo ici</div>
    <nav>
        <?php
        echo '<a href="accueil">Home</a>';
        if (isset($_SESSION['user'])) {
            echo '<a href="mes_reservation">Mes réservations</a>';
            // Vérifiez si l'utilisateur a un niveau d'accès d'administrateur (id_level = 2)
            if (isset($_SESSION['id_level']) && $_SESSION['id_level'] == 2) {
                echo '<a href="admin">Admin</a>';
            }

            // Générer le jeton CSRF pour le formulaire de déconnexion
            $csrfToken = generate_csrf_token();

            echo '<form method="POST" action="deconnexion" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="' . $csrfToken . '">
                    <button type="submit"><img src="Assets/images/icon/deconexion.png" alt="deconexion" class="deconexion"></button>
                  </form>';
        } else {
            echo '<a href="inscription">Inscription</a>';
            echo '<a href="connexion">Connexion</a>';
        }
        ?>
    </nav>
</header>