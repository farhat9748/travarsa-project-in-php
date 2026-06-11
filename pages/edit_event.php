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
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id'];
    $eventName = $_POST['event_name'];
    $eventDate = $_POST['event_date'];
    $eventLocation = $_POST['event_location'];
    $eventDescription = $_POST['event_description'];

    // Handle file upload if a new image is provided
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
            $dest_path = '../uploads/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Update event with new image path
                $sqlUpdate = "UPDATE events SET name='$eventName', date='$eventDate', location='$eventLocation', description='$eventDescription', image_path='$dest_path' WHERE id=$eventId";
            } else {
                header("Location: edit_event.php?id=$eventId&error=Error moving the uploaded file");
                exit();
            }
        } else {
            header("Location: edit_event.php?id=$eventId&error=Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
            exit();
        }
    } else {
        // Update event without changing the image
        $sqlUpdate = "UPDATE events SET name='$eventName', date='$eventDate', location='$eventLocation', description='$eventDescription' WHERE id=$eventId";
    }

    if (mysqli_query($conn, $sqlUpdate)) {
        header("Location: events.php?message=Event updated successfully");
        exit();
    } else {
        header("Location: edit_event.php?id=$eventId&error=Error updating event: " . mysqli_error($conn));
        exit(); 
    }
}


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
    <title>Edit Event</title>
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
            <form action="edit_event.php" method="POST">
                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                    <div class="mb-3">
                        <label for="eventName" class="form-label">Event Name</label>
                        <input type="text" class="form-control" id="eventName" name="event_name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDate" class="form-label">Event Date</label>
                        <input type="date" class="form-control" id="eventDate" name="event_date" value="<?php echo htmlspecialchars($row['date']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventLocation" class="form-label">Event Location</label>
                        <input type="text" class="form-control" id="eventLocation" name="event_location" value="<?php echo htmlspecialchars($row['location']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Event Description</label>
                        <textarea class="form-control" id="eventDescription" name="event_description" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="inputGroupFile01">Upload Image</label>
                        <input type="file" class="form-control" id="inputGroupFile01" name="event_image">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                </form>
        </div>                    
    

</div>

<!-- Bootstrap 5 JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>