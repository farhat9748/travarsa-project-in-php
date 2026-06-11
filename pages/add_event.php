<?php
session_start();

// 1. AUTHENTICATION CHECKS
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /travarsa/index.php?error=Please log in to access the dashboard");
    exit();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: /travarsa/index.php?error=You do not have permission to access the dashboard");
    exit();
}

// 2. TOAST NOTIFICATIONS
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

// 3. POST REQUEST HANDLING
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../includes/db.php';

    $eventName = $_POST['event_name'];
    $eventDate = $_POST['event_date'];
    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];
    $dest_path = null; // Default to null if no image is uploaded

    // Handle file upload securely
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['event_image']['tmp_name'];
        $fileName = $_FILES['event_image']['name'];
        $fileSize = $_FILES['event_image']['size'];
        $fileType = $_FILES['event_image']['type'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Validate allowed extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            
            // Create a completely unique name for security
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // Absolute or correct relative pathway configuration
            $uploadFileDir = '../assets/uploads/';
            
            // Check if folder exists, if not, create it
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                header("Location: /travarsa/pages/add_event.php?error=Failed moving uploaded image filesystem file.");
                exit();
            }
        } else {
            header("Location: /travarsa/pages/add_event.php?error=Invalid file type. Only JPG, JPEG, PNG, & GIF allowed.");
            exit();
        }
    }

    // Secure database storage via Secure Prepared Statements
    $sql = "INSERT INTO events (name, date, location, description, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters ("sssss" represents 5 string fields)
        mysqli_stmt_bind_param($stmt, "sssss", $eventName, $eventDate, $eventLocation, $eventDescription, $dest_path);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: /travarsa/pages/events.php?message=Event added successfully!");
            exit();
        } else {
            header("Location: /travarsa/pages/events.php?error=Database Execution Failure: " . mysqli_stmt_error($stmt));
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        header("Location: /travarsa/pages/events.php?error=Database Preparation Failure: " . mysqli_error($conn));
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex w-100">

    <!-- Include your permanent sidebar component -->
    <?php include '../includes/sidebar.php'; ?>

    <!-- Main Content Panel -->
    <div id="page-content-wrapper" class="bg-white flex-grow-1" style="min-height: 100vh;">
        <!-- Top Navigation / Header -->
        <header class="navbar navbar-light bg-light border-bottom p-3 mb-4">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Add Event</span>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="container-fluid px-4">
            <div class="card p-4 shadow-sm">
                <h5 class="card-title">Add New Event</h5>
                <p class="card-text">Fill out the form below to add a new event.</p>
            </div>
            
            <div class="card p-4 shadow-sm mt-4">
                <!-- IMPORTANT: enctype="multipart/form-data" tells your browser to allow raw file data parsing stream -->
                <form action="add_event.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="eventName" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="eventName" name="event_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="eventDate" name="event_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventLocation" class="form-label">Event Location</label>
                        <input type="text" class="form-control" id="eventLocation" name="event_location" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Event Description</label>
                        <textarea class="form-control" id="eventDescription" name="event_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputGroupFile01">Upload Image</label>
                        <input type="file" class="form-control" id="inputGroupFile01" name="event_image">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>    
    </div>

</div>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>