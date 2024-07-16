<?php
namespace Views;

class RegisterView { 
    public function initForm() {
        $csrf_token = generate_csrf_token();
        echo '<h1>Créer un compte</h1>
        <form class="vertical" action="inscription" method="post" id="register-form">
            <input type="hidden" name="csrf_token" value="' . $csrf_token . '">
            <label for="username">Nom d\'utilisateur</label>
            <input type="text" name="username" id="username" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            
            <button type="submit">Envoyer</button>
            <div id="error-message" style="color: red;"></div>
        </form>
        <script>
            document.getElementById("register-form").addEventListener("submit", function(event) {
                event.preventDefault();
                var username = document.getElementById("username").value;
                var email = document.getElementById("email").value;
                var errorMessage = document.getElementById("error-message");

                fetch(`check_user.php?email=${encodeURIComponent(email)}&username=${encodeURIComponent(username)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.emailExists) {
                            errorMessage.textContent = "Cet email existe déjà.";
                        } else if (data.usernameExists) {
                            errorMessage.textContent = "Ce nom d\'utilisateur existe déjà.";
                        } else {
                            errorMessage.textContent = "";
                            document.getElementById("register-form").submit();
                        }
                    })
                    .catch(error => {
                        errorMessage.textContent = "Erreur de vérification des données.";
                    });
            });
        </script>';
    }
}
?>
