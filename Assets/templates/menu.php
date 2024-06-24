<div class="logo">inserer logo ici</div>
<nav>
    <?php
    if (isset($_SESSION['user'])) {
        echo '<a href="profile.php">Profil</a>';
        echo '<a href="reservations.php">Mes réservations</a>';
        echo '<form action="router.php?action=logout" method="POST" style="display:inline;"><button type="submit">Déconnexion</button></form>';
    } else {
        echo '<a href="router.php?action=inscription">Inscription</a>';
        echo '<a href="router.php?action=login">Connexion</a>';
    }
    ?>
</nav>