<?php
require 'header.php';
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="dash-layout">
    <?php require 'dashboardverticalcontent.php'; ?>

    <main class="dash-main">
        <?php require 'dashboardheader.php'; ?>
        <h2>Dashboard</h2>
        <p>You are logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>
    </main>
</div>

<?php require 'footer.php'; ?>
