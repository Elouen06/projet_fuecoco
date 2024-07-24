<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

// Fonction pour déclencher l'erreur 418
function trigger_error_418() {
    header("HTTP/1.1 418 I'm a teapot");
    include('418.php');
    exit();
}

// Déclenchement manuel de l'erreur 418
// Décommentez la ligne suivante pour déclencher l'erreur 418
// trigger_error_418();

require_once TMP . 'top.php';
require_once TMP . 'menu.php';
require_once 'router.php';
require_once TMP . 'bottom.php';
?>
