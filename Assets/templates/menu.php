<div class="logo">inserer logo ici</div>
<nav>
    <?php
    if (isset($_SESSION['user'])) {
        echo '<a href="?action=login">Profil</a>';
        echo '<a href="reservations.php">Mes réservations</a>';
    } else {
        echo '<a href="?action=inscription">Inscription</a>';
        echo '<a href="?action=login">Connexion</a>';
    }
    ?>
</nav>