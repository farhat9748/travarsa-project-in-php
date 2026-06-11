<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /travarsa/index.php?error=Please log in to access the dashboard");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: /travarsa/index.php?error=You do not have permission to access the dashboard");
    exit();
}
$sql = "SELECT b.id, b.booking_date_time, b.number_of_guest, u.username, e.name 
        FROM bookings b
        JOIN users u ON b.uid = u.id
        JOIN events e ON b.e_id = e.id
        ORDER BY b.booking_date_time DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    header("Location: /travarsa/index.php?error=Error fetching bookings: " . mysqli_error($conn));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- The d-flex wrapper aligns the sidebar and content columns side-by-side -->
<div class="d-flex w-100">

    <!-- Include your permanent sidebar component -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content Panel -->
    <div id="page-content-wrapper" class="bg-white flex-grow-1" style="min-height: 100vh;">
        <!-- Top Navigation / Header -->
        <header class="navbar navbar-light bg-light border-bottom p-3 mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Bookings</span>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="container-fluid px-4">
            <div class="card p-4 shadow-sm">
                <h5 class="card-title">All Bookings</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Booking ID</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Number of Guests</th>
                                <th scope="col">Username</th>
                                <th scope="col">Event Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booking_date_time']); ?></td>
                                    <td><?php echo htmlspecialchars($row['number_of_guest']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>