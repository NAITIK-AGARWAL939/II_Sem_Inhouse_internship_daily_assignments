<?php
require 'header.php';
require 'db_connect.php';
require 'checkLoginError.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier']);
    $password   = $_POST['password'];

    $result = validateLogin($conn, $identifier, $password);

    if ($result['success']) {
        $_SESSION['user_id']  = $result['user']['id'];
        $_SESSION['username'] = $result['user']['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['loginError'] = $result['error'];
        header("Location: login.php");
        exit();
    }
}
?>

<p class="status-banner" style="text-align:center; padding-top:24px;">Connection Successful!</p>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Login</h2>

        <?php if (isset($_SESSION['loginError'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['loginError']); unset($_SESSION['loginError']); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" data-validate>
            <div class="field-group">
                <label for="identifier">Username or Email</label>
                <input type="text" id="identifier" name="identifier" required>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-primary">Login</button>
        </form>

        <p class="auth-footer-note">Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</div>

<?php require 'footer.php'; ?>
