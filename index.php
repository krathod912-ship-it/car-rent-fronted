<?php
$pageTitle = 'Home';
include __DIR__ . '/partials/header.php';
?>

<?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        You have been logged out successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center text-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">
                    Rent the <span class="text-primary">Perfect Car</span> for Every Journey
                </h1>
                <p class="lead mb-4">
                Start your journey today with our easy, affordable, and reliable car rental service — your perfect ride is just a click away! 🚗✨                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="booking.php" class="btn btn-primary btn-lg">
                        Book Now
                    </a>
                    <a href="search.php" class="btn btn-outline-light btn-lg">
                        Explore Cars
                    </a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center position-relative">
                <div class="hero-car-card shadow-lg rounded-4 bg-light text-dark p-4">
                    <h5 class="fw-bold mb-2">Featured Car</h5>
                    <p class="mb-1">2024 Tesla Model 3 · Automatic · Electric</p>
                    <p class="h4 text-primary mb-3">₹4,499 / day</p>
                    <div class="d-flex justify-content-between small text-muted">
                        <span>5 Seats</span>
                        <span>Airbags</span>
                        <span>GPS</span>
                        <span>AC</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Search Strip -->
<section class="py-4 bg-body-tertiary border-bottom">
    <div class="container">
        <form id="quickSearchForm" class="row g-3 align-items-end quick-search-form" method="get" action="search.php" novalidate>
            <div class="col-md-3">
                <label class="form-label" for="pickupLocation">Pickup Location</label>
                <input type="text" class="form-control" id="pickupLocation" name="location" placeholder="e.g., MG Road, Bangalore" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="pickupDate">Pickup Date</label>
                <input type="date" class="form-control" id="pickupDate" name="pickup_date" />
            </div>
            <div class="col-md-3">
                <label class="form-label" for="dropDate">Drop Date</label>
                <input type="date" class="form-control" id="dropDate" name="drop_date" />
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">Search Cars</button>
            </div>
        </form>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Why Choose CarRent?</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Wide Range of Vehicles</h5>
                        <p class="card-text">
                            From compact city cars to luxury SUVs, we have the perfect vehicle for every occasion.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Complete Booking &amp; Payment UPI</h5>
                        <p class="card-text">
                            Experience a seamless booking process with interactive forms, validations, and a mock payment flow.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Affordable Pricing</h5>
                        <p class="card-text">
                            Competitive rates and exclusive discounts make renting a car more accessible than ever. 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promotional Offers Section -->
<section class="py-5 offers-section">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Today's car rental and van rental offers</h2>
        <div class="row g-4 mb-5">
            <!-- Offer Card 1 -->
            <div class="col-lg-6">
                <div class="offer-card offer-card-1 position-relative overflow-hidden rounded-4 text-white" style="min-height: 300px; background: linear-gradient(135deg, rgba(255,110,122,0.92), rgba(255,183,77,0.92)), url('https://images.unsplash.com/photo-1508193638390-0d95b3a9a2a0?w=1200') center/cover;">
                    <div class="offer-badge">
                        <span class="badge-text">Up to</span>
                        <span class="badge-value">15%</span>
                        <span class="badge-text">off</span>
                    </div>
                    <div class="offer-content position-absolute bottom-0 start-0 p-4">
                        <h3 class="fw-bold display-6 mb-0">Love comes first</h3>
                    </div>
                </div>
            </div>

            <!-- Offer Card 2 -->
            <div class="col-lg-6">
                <div class="offer-card offer-card-2 position-relative overflow-hidden rounded-4 text-white" style="min-height: 300px; background: linear-gradient(135deg, rgba(10,25,47,0.88), rgba(33,97,140,0.88)), url('https://images.unsplash.com/photo-1508057198894-247b23fe5ade?w=1200') center/cover;">
                    <div class="offer-badge">
                        <span class="badge-text">UP TO</span>
                        <span class="badge-value">20%</span>
                        <span class="badge-text">OFF</span>
                    </div>
                    <div class="offer-content position-absolute bottom-0 start-0 p-4">
                        <h3 class="fw-bold display-6 mb-0">Enjoy our business deals</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vehicle Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">Our Vehicle Categories</h2>
        <div class="row g-4 mb-5">
            <!-- City Car -->
            <div class="col-lg-4 col-md-6">
                <div class="vehicle-category-card h-100">
                    <h4 class="fw-bold mb-3">City car</h4>
                    <p class="text-muted mb-3">
                        Compact, fuel-efficient, and perfect for navigating busy city streets. Choose from Tata Nexon, Maruti Baleno, and Hyundai i20.
                    </p>
                    <a href="search.php?type=city" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>

            <!-- Luxury Car -->
            <div class="col-lg-4 col-md-6">
                <div class="vehicle-category-card h-100">
                    <h4 class="fw-bold mb-3">Luxury Car</h4>
                    <p class="text-muted mb-3">
                        Experience premium comfort and style with our luxury collection. BMW X5, Mercedes C-Class, and Audi A4 await you.
                    </p>
                    <a href="search.php?type=luxury" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>

            <!-- Family Car -->
            <div class="col-lg-4 col-md-6">
                <div class="vehicle-category-card h-100">
                    <h4 class="fw-bold mb-3">Family Car</h4>
                    <p class="text-muted mb-3">
                        Spacious 7-seater vehicles for comfortable family trips. Toyota Innova, Mahindra XUV700, and Kia Carens available.
                    </p>
                    <a href="search.php?type=family" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

