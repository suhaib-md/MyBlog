<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Then do your authentication checks below...
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
