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


$sql = "SELECT * FROM events";
$result = mysqli_query($conn, $sql);


if (isset($_GET['error'])) {
    echo '<div class="toast show position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">';
    echo '  <div class="toast-header bg-danger text-white">';
    echo '    <strong class="me-auto">Error</strong>';
    echo '    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>';
    echo '  </div>'; // Header ends here
    echo '  <div class="toast-body bg-danger text-white">' . htmlspecialchars($_GET['error']) . '</div>'; // Body is separate
    echo '</div>';
}

if (isset($_GET['message'])) {
    echo '<div class="toast show position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 1050;">';
    echo '  <div class="toast-header bg-success text-white">';
    echo '    <strong class="me-auto">Success</strong>';
    echo '    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>';
    echo '  </div>'; // Header ends here
    echo '  <div class="toast-body bg-success text-white">' . htmlspecialchars($_GET['message']) . '</div>'; // Body is separate
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
        <div class="container-fluid px-4">
            <a href="add_event.php" class="btn btn-primary mb-4">Add New Event</a>
        </div>

        <!-- Dynamic Content Body -->
        <div class="container-fluid px-4">
            <div class="card p-4 shadow-sm">
                <h5 class="card-title">Upcoming Events</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Location</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td>
                                        <a href="view_event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                                        <a href="edit_event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_event.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>                    
    

</div>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>