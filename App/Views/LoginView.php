<?php
namespace Views;

class LoginView {
    public function loginform() {
        if (isset($_SESSION['user'])) {
            echo '<p>Welcome, ' . htmlspecialchars($_SESSION['user']) . '!</p>
            <form method="POST" action="deconnexion">
                <button type="submit">Logout</button>
            </form>';
        } else {
            $csrf_token = generate_csrf_token();
            echo '<h1>Connecte-toi</h1>
            <form class="vertical" action="connexion" method="post">
                <input type="hidden" name="csrf_token" value="' . $csrf_token . '">
                <label for="email">Email</label><input type="text" name="email" id="email">
                <label for="password">Mot de passe</label><input type="password" name="password" id="password">
                <button>Se connecter</button>
            </form>
            <a href="connexion/oublie">Mot de passe oublié ?</a>';
        }
    }
}
