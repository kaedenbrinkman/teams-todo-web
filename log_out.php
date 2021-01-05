<?php
include 'header.php';
$_SESSION = [];
session_unset();
session_destroy();
header('Location: /');