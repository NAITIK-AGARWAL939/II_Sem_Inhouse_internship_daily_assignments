<?php
session_start();
require 'db_connect.php';
require 'checkRegistrationError.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $error = validateRegistration($conn, $fullname, $username, $email, $password, $confirm);

    if ($error) {
        $_SESSION['registerError'] = $error;
        header("Location: registration.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: success.PHP");
        exit();
    } else {
        $_SESSION['registerError'] = "Something went wrong. Please try again.";
        header("Location: registration.php");
        exit();
    }
}
