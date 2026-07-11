<?php
// checkProfileError.php
// Returns an error message string, or null if everything is valid

function validateProfileUpdate($conn, $userId, $fullname, $username, $email) {
    if (empty($fullname) || empty($username) || empty($email)) {
        return "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }

    // Make sure the new username/email isn't already taken by someone ELSE
    $stmt = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $stmt->bind_param("ssi", $username, $email, $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return "That username or email is already in use by another account.";
    }

    return null;
}
