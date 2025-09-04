<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Delete session cookie (important for full logout)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// Finally destroy the session
session_destroy();

// Optional: clear any custom auth cookies too (if used)
// setcookie("auth_token", "", time() - 3600, "/");

header("Location: welcome.html");
exit();
?>
