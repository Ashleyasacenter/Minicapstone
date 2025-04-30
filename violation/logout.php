<?php
require_once 'config.php';
require_once 'auth.php';

// Destroy all session data
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>