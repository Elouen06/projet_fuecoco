<?php
session_start();

const CSS = 'Assets/css/';
const JS = 'Assets/js/';
const TMP = 'Assets/templates/';

require_once TMP . 'top.php';
require_once TMP . 'menu.php';
require_once 'router.php';
require_once TMP . 'bottom.php';
?>
