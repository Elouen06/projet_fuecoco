<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';

require_once TMP . 'top.php';
require_once TMP . 'menu.php';
require_once 'router.php';
require_once TMP . 'bottom.php';
?>
