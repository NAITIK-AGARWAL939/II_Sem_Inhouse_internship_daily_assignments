<?php
require_once "config.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $errors = [];

    
    if ($fullname === '') {
        $errors[] = "Full name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }

    if (strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        try {
            
            $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
            $checkStmt->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            if ($checkStmt->rowCount() > 0) {
                echo "Username or email already exists.";
            } else {
                
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password) 
                                        VALUES (:fullname, :email, :username, :password)");

                $stmt->execute([
                    ':fullname' => $fullname,
                    ':email' => $email,
                    ':username' => $username,
                    ':password' => $hashedPassword
                ]);

                echo "Registration successful!";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    } else {
        
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }

} else {
    echo "Invalid request method.";
}
?>
