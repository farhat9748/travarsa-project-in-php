<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $checkQuery = "SELECT * FROM users WHERE username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        header("Location: /travarsa/index.php?error=Username already exists");
        exit();

    } else {
        // Insert the new user into the database
        $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        if (mysqli_query($conn, $insertQuery)) {
            header("Location: /travarsa/index.php?message=Registration successful! You can now log in.");
            exit();
        } else {
            header("Location: /travarsa/index.php?error=Error occurred during registration.");
            exit();
        }
    }
}