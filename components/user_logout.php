<?php

// Include the database connection if required
@include('connect.php');

// Start or resume the session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear any session cookies (optional but recommended for complete logout)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to the home page
header("Location: ../home.php");
exit;

?>
