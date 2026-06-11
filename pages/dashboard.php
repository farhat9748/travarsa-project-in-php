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

// Dashboard overview counts
$eventCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_events FROM events");
$eventCount = mysqli_fetch_assoc($eventCountResult)['total_events'] ?? 0;
$bookingCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_bookings FROM bookings");
$bookingCount = mysqli_fetch_assoc($bookingCountResult)['total_bookings'] ?? 0;
$userCountResult = mysqli_query($conn, "SELECT COUNT(*) AS total_users FROM users");
$userCount = mysqli_fetch_assoc($userCountResult)['total_users'] ?? 0;
$upcomingEventCountResult = mysqli_query($conn, "SELECT COUNT(*) AS upcoming_events FROM events WHERE date >= CURDATE()");
$upcomingEventCount = mysqli_fetch_assoc($upcomingEventCountResult)['upcoming_events'] ?? 0;

// Bookings by event chart data
$eventBookingData = [["Event", "Bookings"]];
$eventBookingQuery = "SELECT e.name AS event_name, COUNT(b.id) AS bookings_count FROM events e LEFT JOIN bookings b ON e.id = b.e_id GROUP BY e.id ORDER BY bookings_count DESC LIMIT 8";
$eventBookingResult = mysqli_query($conn, $eventBookingQuery);
while ($row = mysqli_fetch_assoc($eventBookingResult)) {
    $eventBookingData[] = [htmlspecialchars($row['event_name']), (int) $row['bookings_count']];
}

// Monthly bookings chart data
$monthlyBookingData = [["Month", "Bookings"]];
$monthlyBookingQuery = "SELECT DATE_FORMAT(booking_date_time, '%Y-%m') AS booking_month, COUNT(*) AS total_bookings FROM bookings GROUP BY booking_month ORDER BY booking_month ASC";
$monthlyBookingResult = mysqli_query($conn, $monthlyBookingQuery);
while ($row = mysqli_fetch_assoc($monthlyBookingResult)) {
    $monthlyBookingData[] = [htmlspecialchars($row['booking_month']), (int) $row['total_bookings']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<div class="d-flex w-100">
    <?php include '../includes/sidebar.php'; ?>

    <div id="page-content-wrapper" class="bg-white flex-grow-1" style="min-height: 100vh;">
        <header class="navbar navbar-light bg-light border-bottom p-3 mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Dashboard Overview</span>
            </div>
        </header>

        <div class="container-fluid px-4">
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Total Events</h6>
                            <h2 class="fw-bold mb-0"><?php echo $eventCount; ?></h2>
                            <p class="text-muted mb-0">All events currently stored in the system.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Bookings</h6>
                            <h2 class="fw-bold mb-0"><?php echo $bookingCount; ?></h2>
                            <p class="text-muted mb-0">Total bookings made by travelers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Users</h6>
                            <h2 class="fw-bold mb-0"><?php echo $userCount; ?></h2>
                            <p class="text-muted mb-0">Registered users in the application.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Upcoming Events</h6>
                            <h2 class="fw-bold mb-0"><?php echo $upcomingEventCount; ?></h2>
                            <p class="text-muted mb-0">Events scheduled for today or later.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Bookings by Event</h5>
                            </div>
                            <div id="event-bookings-chart" style="height: 380px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Monthly Booking Trend</h5>
                            </div>
                            <div id="monthly-bookings-chart" style="height: 380px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawEventBookingsChart();
        drawMonthlyBookingsChart();
    }

    function drawEventBookingsChart() {
        const data = google.visualization.arrayToDataTable(<?php echo json_encode($eventBookingData, JSON_UNESCAPED_UNICODE); ?>);
        const options = {
            title: 'Bookings by Event',
            legend: { position: 'none' },
            height: 360,
            chartArea: { width: '75%', height: '70%' },
            hAxis: { title: 'Event' },
            vAxis: { title: 'Bookings', minValue: 0 },
            colors: ['#2563eb'],
        };
        const chart = new google.visualization.ColumnChart(document.getElementById('event-bookings-chart'));
        chart.draw(data, options);
    }

    function drawMonthlyBookingsChart() {
        const data = google.visualization.arrayToDataTable(<?php echo json_encode($monthlyBookingData, JSON_UNESCAPED_UNICODE); ?>);
        const options = {
            title: 'Monthly Bookings',
            legend: { position: 'none' },
            height: 360,
            chartArea: { width: '75%', height: '70%' },
            hAxis: { title: 'Month' },
            vAxis: { title: 'Bookings', minValue: 0 },
            colors: ['#0f766e'],
        };
        const chart = new google.visualization.LineChart(document.getElementById('monthly-bookings-chart'));
        chart.draw(data, options);
    }

    window.addEventListener('resize', drawCharts);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>