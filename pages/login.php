<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username and password are correct
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = $user['role']; // Store user role in session
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            if ($user['role'] === 'admin') {
                header("Location: /travarsa/pages/dashboard.php");
            } else {
                header("Location: /travarsa/index.php?message=Login successful!");
                exit();
            }
        } else {
            header("Location: /travarsa/index.php?error=Invalid username or password");
            exit();
        }
    } else {
        header("Location: /travarsa/index.php?error=Invalid username or password");
        exit();
    }
}