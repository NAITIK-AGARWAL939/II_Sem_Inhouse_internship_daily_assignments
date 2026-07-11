<?php
require 'header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="page-content">
    <h2>About Us</h2>
    <p>We are leading tech company in INDIA and other countries</p>
</div>

<?php require 'footer.php'; ?>
