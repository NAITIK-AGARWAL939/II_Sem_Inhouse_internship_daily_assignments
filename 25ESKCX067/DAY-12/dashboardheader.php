<?php
// dashboardheader.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<p class="welcome-banner">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
