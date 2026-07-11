<?php
require 'header.php';
require 'db_connect.php';
require 'checkProfileError.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Handle profile update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);

    $error = validateProfileUpdate($conn, $userId, $fullname, $username, $email);

    if ($error) {
        $_SESSION['profileError'] = $error;
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname = ?, username = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fullname, $username, $email, $userId);
        $stmt->execute();

        // Keep the session username in sync
        $_SESSION['username'] = $username;
        $_SESSION['profileSuccess'] = "Profile updated successfully.";
    }
    header("Location: profile.php");
    exit();
}

// Fetch current profile data
$stmt = $conn->prepare("SELECT fullname, username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<div class="dash-layout">
    <?php require 'dashboardverticalcontent.php'; ?>

    <main class="dash-main">
        <?php require 'dashboardheader.php'; ?>

        <div class="auth-card" style="max-width:520px; margin:0;">
            <h2>My Profile</h2>

            <?php if (isset($_SESSION['profileError'])): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['profileError']); unset($_SESSION['profileError']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['profileSuccess'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['profileSuccess']); unset($_SESSION['profileSuccess']); ?></div>
            <?php endif; ?>

            <form action="profile.php" method="POST" data-validate>
                <div class="field-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>

                <div class="field-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="field-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <button type="submit" class="btn-primary">Save Changes</button>
            </form>

            <p class="auth-footer-note">
                Member since <?php echo date("F j, Y", strtotime($user['created_at'])); ?><br>
                Want to change your password instead? <a href="updatePassword.php">Go here</a>
            </p>
        </div>
    </main>
</div>

<?php require 'footer.php'; ?>
