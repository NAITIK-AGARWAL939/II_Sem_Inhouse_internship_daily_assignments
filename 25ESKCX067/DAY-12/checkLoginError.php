<?php
// checkLoginError.php
// Returns an array: ['success' => bool, 'user' => array|null, 'error' => string|null]

function validateLogin($conn, $identifier, $password) {
    if (empty($identifier) || empty($password)) {
        return ['success' => false, 'user' => null, 'error' => "Please enter both fields."];
    }

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ['success' => false, 'user' => null, 'error' => "No account found with that username/email."];
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'user' => null, 'error' => "Incorrect password."];
    }

    return ['success' => true, 'user' => $user, 'error' => null];
}
