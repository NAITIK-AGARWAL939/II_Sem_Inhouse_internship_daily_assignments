<?php
// checkRegistrationError.php
// Returns an error message string, or null if everything is valid

function validateRegistration($conn, $fullname, $username, $email, $password, $confirm) {
    if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirm)) {
        return "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }

    if (strlen($password) < 6) {
        return "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm) {
        return "Passwords do not match.";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        return "Username or email is already taken.";
    }

    return null;
}
