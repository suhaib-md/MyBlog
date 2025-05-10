<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
    .navbar {
        background-color: #1a1a1a;
        padding: 14px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar a {
        color: #f1f1f1;
        text-decoration: none;
        margin-right: 20px;
        font-size: 16px;
        transition: color 0.2s ease;
    }

    .navbar a:hover {
        color: #00acee;
    }

    .navbar-left {
        display: flex;
        align-items: center;
    }

    .navbar-right {
        display: flex;
        align-items: center;
    }

    .navbar-logo {
        font-size: 20px;
        font-weight: bold;
        margin-right: 30px;
        color: #00acee;
        text-decoration: none;
    }
</style>

<nav class="navbar">
    <div class="navbar-left">
        <a class="navbar-logo" href="index.php">MyBlog</a>
        <a href="index.php">Home</a>
    </div>
    <div class="navbar-right">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</nav>
