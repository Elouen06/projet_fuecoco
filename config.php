<?php
session_start();

define('BASE_URL', 'http://localhost/projet_fuecoco/');
define('CON', 'Assets/cron/');
define('CSS', 'Assets/css/');
define('IMG', 'Assets/images/');
define('JS', 'Assets/js/');
define('TMP', 'Assets/templates/');

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>
