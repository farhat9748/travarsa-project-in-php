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
    $sql = "SELECT * FROM events WHERE id = $eventId";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        header("Location: events.php?error=Event not found");
        exit();
    }
} else {
    header("Location: events.php?error=No event ID provided");
    exit();
}
if (isset($_GET['error'])) {
    echo '<div class="toast show position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">';
    echo '  <div class="toast-header bg-danger text-white">';
    echo '    <strong class="me-auto">Error</strong>';
    echo '    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>';
    echo '  </div>';
    echo '  <div class="toast-body bg-danger text-white">' . htmlspecialchars($_GET['error']) . '</div>';
    echo '</div>';
}
if (isset($_GET['message'])) {
    echo '<div class="toast show position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">';
    echo '  <div class="toast-header bg-success text-white">';
    echo '    <strong class="me-auto">Success</strong>';
    echo '    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>';
    echo '  </div>';
    echo '  <div class="toast-body bg-success text-white">' . htmlspecialchars($_GET['message']) . '</div>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
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
                <span class="navbar-brand mb-0 h1">Events</span>
            </div>
        </header>
        

        <!-- Dynamic Content Body -->
                           
        <div class="container-fluid px-4">
            <div class="card p-4 shadow-sm">
                <h5 class="card-title">Event Details</h5>
                <p class="card-text"><strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?></p>
                <p class="card-text"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                <p class="card-text"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <?php if (!empty($row['image'])) { ?>
                    <div class="mt-3">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Event Image" class="img-fluid rounded">
                    </div>
                <?php } ?>

                <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn btn-warning mt-4">Edit Event</a>
                <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn btn-danger mt-4" onclick="return confirm('Are you sure you want to delete this event?');">Delete Event</a>
                </div>
    

</div>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>