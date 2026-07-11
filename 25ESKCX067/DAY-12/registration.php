<?php require 'header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Create an Account</h2>

        <?php if (isset($_SESSION['registerError'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['registerError']); unset($_SESSION['registerError']); ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST" data-validate>
            <div class="field-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" required>
            </div>

            <div class="field-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="field-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="field-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="field-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn-primary">Register</button>
        </form>

        <p class="auth-footer-note">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php require 'footer.php'; ?>
