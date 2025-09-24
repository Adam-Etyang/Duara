<?php
session_start();

// Clear all session data
$_SESSION = [];
session_unset();
session_destroy();

// Optional: clear cookies (like "remember me")
if (isset($_COOKIE['remember_email'])) {
    setcookie('remember_email', '', time() - 3600, '/');
}

// Redirect back to login with a success message
header("Location: login.php?logout=1");
exit();
