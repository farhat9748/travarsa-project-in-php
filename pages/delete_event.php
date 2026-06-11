<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)
{
    header("Location: /travarsa/index.php?error=Please log in to access the events page");
    exit();
}
if ($_SESSION['role'] !== 'admin')
{
    header("Location: /travarsa/index.php?error=You do not have permission to access the events page");
    exit();
}
include '../includes/db.php';


if (isset($_GET['id'])) {
    $eventId = $_GET['id'];
    $sql = "DELETE FROM events WHERE id = $eventId";
    if (mysqli_query($conn, $sql)) {
        header("Location: events.php?message=Event deleted successfully");
        exit();
    } else {
        header("Location: events.php?error=Error deleting event: " . mysqli_error($conn));
        exit();
    }
} else {
    header("Location: events.php?error=No event ID provided");
    exit();
}