<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /travarsa/index.php?error=You must be logged in to confirm a booking");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userid = $_SESSION['user_id'];
    $eventid = $_POST['event_id'];
    $guests = $_POST['guests'];
    $sql = "INSERT INTO bookings (booking_date_time, number_of_guest, uid, e_id) VALUES (NOW(), $guests, $userid, $eventid)";
    if (mysqli_query($conn, $sql)) {
        header("Location: /travarsa/index.php?message=Booking confirmed successfully");
        exit();
    } else {
        header("Location: /travarsa/index.php?error=Error confirming booking: " . mysqli_error($conn));
        exit();
    }
} else {
    header("Location: /travarsa/index.php?error=Invalid request method");
    exit();
}