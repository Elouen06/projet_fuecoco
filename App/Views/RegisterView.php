<?php
namespace Views;

class RegisterView { 
    public function initForm() {
        $csrf_token = generate_csrf_token();
        echo '<h1>Créer un compte</h1>
        <form class="vertical" action="?action=inscription" method="post">
            <input type="hidden" name="csrf_token" value="' . $csrf_token . '">
            <label for="username">Nom d\'utilisateur</label>
            <input type="text" name="username" id="username" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Envoyer</button>
        </form>';
    }
}
?>
