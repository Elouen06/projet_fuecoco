<?php
namespace Views;

class ForgotPasswordView {
    public function showForm() {
        $csrf_token = generate_csrf_token();
        echo '<h1>Forgot Password</h1>
        <form class="vertical" action="connexion/oublie" method="post">
            <input type="hidden" name="csrf_token" value="' . $csrf_token . '">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Send Reset Link</button>
        </form>';
    }

    public function showResetForm($token) {
        $csrf_token = generate_csrf_token();
        echo '<h1>Reset Password</h1>
        <form class="vertical" action="connexion/reinitialisation" method="post">
            <input type="hidden" name="csrf_token" value="' . $csrf_token . '">
            <input type="hidden" name="token" value="' . $token . '">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Reset Password</button>
        </form>';
    }
}
