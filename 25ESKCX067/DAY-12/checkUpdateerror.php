<?php
// checkUpdateerror.php
// Returns an error message string, or null if everything is valid

function validatePasswordUpdate($conn, $userId, $currentPassword, $newPassword, $confirmPassword) {
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        return "All fields are required.";
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        return "Current password is incorrect.";
    }

    if (strlen($newPassword) < 6) {
        return "New password must be at least 6 characters long.";
    }

    if ($newPassword !== $confirmPassword) {
        return "New passwords do not match.";
    }

    return null;
}
