<?php
include 'includes/header.php';
include 'includes/db.php';
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


$sql = "SELECT * FROM events ORDER BY date DESC LIMIT 4";
$result = mysqli_query($conn, $sql);

?>

<!-- Hero Section -->
<div class="p-5 mb-4 bg-light rounded-3 text-center" style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80') no-repeat center center; background-size: cover; color: white;">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Welcome to Travarsa</h1>
        <p class="col-md-8 mx-auto fs-4">Your ultimate travel companion for exploring the world!</p>
        <button class="btn btn-primary btn-lg" type="button">Start Your Journey</button>
    </div>
</div>

<div class="container mt-5">
    <!-- Features Grid -->
    <div class="row g-4">
        <!-- Feature 1: Destinations -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&h=400&q=80" class="card-img-top" alt="Tropical paradise beach destination">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Explore Destinations</h5>
                    <p class="card-text text-muted">Discover amazing places around the globe with our expertly curated travel guides, itineraries, and local insider recommendations.</p>
                    <a href="destinations.php" class="btn btn-primary mt-auto">Find Places</a>
                </div>
            </div>
        </div>
        
        <!-- Feature 2: Travel Tips -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=600&h=400&q=80" class="card-img-top" alt="Traveler mapping out a journey">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Travel Tips & Tricks</h5>
                    <p class="card-text text-muted">Get expert advice on smart packing, travel safety, budgeting, and hidden hacks to make the most out of your upcoming experiences.</p>
                    <a href="tips.php" class="btn btn-primary mt-auto">Read Guides</a>
                </div>
            </div>
        </div>

        <!-- Feature 3: Bookings & Deals (Added to complete the layout) -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=600&h=400&q=80" class="card-img-top" alt="Airplane flying through clouds">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">Exclusive Travel Deals</h5>
                    <p class="card-text text-muted">Unlock specialized discounts on flights, highly-rated accommodations, and unforgettable tour experiences negotiated just for you.</p>
                    <a href="deals.php" class="btn btn-primary mt-auto">View Offers</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events Section -->
<div class="container mt-5">
    <h2 class="mb-4">Upcoming Events</h2>
    <div class="row g-4">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($row['image'])) { ?>
                        <img src="<?php echo str_replace('../', '/travarsa/', htmlspecialchars($row['image'])); ?>" class="card-img-top" alt="Event Image" style="height: 200px; object-fit: cover;">
                    <?php } else { ?>
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&h=400&q=80" class="card-img-top" alt="Default Event Image">
                    <?php } ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars($row['date']); ?>
                            <br>
                            <?php echo htmlspecialchars($row['location']); ?>
                        </p>
                        <a href="pages/book_event.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto">Book Now</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php
include 'includes/footer.php';
?>