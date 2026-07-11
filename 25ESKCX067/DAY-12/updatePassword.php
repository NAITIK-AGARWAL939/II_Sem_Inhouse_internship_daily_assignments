<?php
require 'header.php';
require 'db_connect.php';
require 'checkUpdateerror.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_new_password'];

    $error = validatePasswordUpdate($conn, $_SESSION['user_id'], $currentPassword, $newPassword, $confirmPassword);

    if ($error) {
        $_SESSION['updateError'] = $error;
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed, $_SESSION['user_id']);
        $stmt->execute();
        $_SESSION['updateSuccess'] = "Password updated successfully.";
    }
    header("Location: updatePassword.php");
    exit();
}
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Change Password</h2>

        <?php if (isset($_SESSION['updateError'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['updateError']); unset($_SESSION['updateError']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['updateSuccess'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['updateSuccess']); unset($_SESSION['updateSuccess']); ?></div>
        <?php endif; ?>

        <form action="updatePassword.php" method="POST" data-validate>
            <div class="field-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="field-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="field-group">
                <label for="confirm_new_password">Confirm New Password</label>
                <input type="password" id="confirm_new_password" name="confirm_new_password" required>
            </div>

            <button type="submit" class="btn-primary">Update Password</button>
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>
