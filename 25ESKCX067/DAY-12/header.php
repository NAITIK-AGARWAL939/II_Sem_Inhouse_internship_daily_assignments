<?php
// header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyApp</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo-group">
    <img src="logo.png" alt="MyApp logo" class="logo-img">
    
</div>

    <ul class="nav-links">
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="dashboard.php">Services</a></li>
        <li><a href="dashboard.php">Gallery</a></li>
        <li><a href="about.php">Contact</a></li>
    </ul>

    <?php if ($isLoggedIn): ?>
        <a href="logout.php" class="btn-nav-login">Logout</a>
    <?php else: ?>
        <a href="login.php" class="btn-nav-login">Login</a>
    <?php endif; ?>
</nav>
