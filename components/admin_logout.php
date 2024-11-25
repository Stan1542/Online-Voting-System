<?php

// Include the database connection if required
@include('connect.php');

// Start or resume the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear all cookies
foreach ($_COOKIE as $key => $value) {
    setcookie($key, '', time() - 3600, '/');
}

// Redirect to the login page
header('location:../admin/login.php');
exit;

?>