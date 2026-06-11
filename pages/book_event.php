<?php
include '../includes/header.php';
include '../includes/db.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: /travarsa/index.php?error=You must be logged in to book an event");
    exit();
}
$userid = $_SESSION['user_id'];
if (!isset($_GET['id'])) {
    header("Location: /travarsa/index.php?error=No event specified");
    exit();
}
$eventid = $_GET['id'];
$sql = "SELECT * FROM events WHERE id = $eventid";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) == 0) {
    header("Location: /travarsa/index.php?error=Event not found");
    exit();
}
$event = mysqli_fetch_assoc($result);
?>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($event['image']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($event['name']); ?>">
            </div>
            <div class="col-md-6">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title fw-bold"><?php echo htmlspecialchars($event['name']); ?></h3>
                    <p class="card-text text-muted"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                    <p class="card-text mt-auto">
                        <strong>Date:</strong> <?php echo date("F j, Y", strtotime($event['date'])); ?>
                        <br>
                        <strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?>
                        <br>
                        <strong>Description:</strong> <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        <br>
                        <strong>Enter Number of Guests:</strong>
                        <br>
                        <form action="confirm_booking.php" method="POST" class="d-flex flex-column">
                            <input type="hidden" name="event_id" value="<?php echo $eventid; ?>">
                            <input type="number" name="guests" class="form-control mb-3" min="1" max="10" required>
                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php
include '../includes/footer.php';
?>