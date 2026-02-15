<?php
$pageTitle = 'Home';
include __DIR__ . '/partials/header.php';
?>

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
        <!-- Submitting this form just opens the Search & Filter page (no JS, demo only) -->
        <form class="row g-3 align-items-end quick-search-form" method="get" action="search.php">
            <div class="col-md-3">
                <label class="form-label">Pickup Location</label>
                <input type="text" class="form-control" placeholder="e.g., MG Road, Bangalore" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Pickup Date</label>
                <input type="date" class="form-control" required />
            </div>
            <div class="col-md-3">
                <label class="form-label">Drop Date</label>
                <input type="date" class="form-control" required />
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
            <div class="col-lg-3 col-md-6">
                <div class="vehicle-category-card h-100">
                    <div class="vehicle-image mb-3">
                        <img src="assets/images/city-car.png" alt="City Car" class="img-fluid rounded-3">
                    </div>
                    <h4 class="fw-bold">City car</h4>
                    <p class="text-muted mb-3">
                        These range from compact and fuel-efficient city to eco-friendly model
                    </p>
                    <a href="search.php" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>

            <!-- Premium -->
            <div class="col-lg-3 col-md-6">
                <div class="vehicle-category-card h-100">
                    <div class="vehicle-image mb-3">
                        <img src="assets/images/premium-car.png" alt="Premium" class="img-fluid rounded-3">
                    </div>
                    <h4 class="fw-bold">Premium</h4>
                    <p class="text-muted mb-3">
                        You can choose from a wide range of premium vehicles made by legendary manufacturers
                    </p>
                    <a href="search.php" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>

            <!-- Electric -->
            <div class="col-lg-3 col-md-6">
                <div class="vehicle-category-card h-100">
                    <div class="vehicle-image mb-3">
                        <img src="assets/images/electric-car.png" alt="Electric" class="img-fluid rounded-3">
                    </div>
                    <h4 class="fw-bold">Electric</h4>
                    <p class="text-muted mb-3">
                        Discover our models of electric, hybrid or plug-in vehicles
                    </p>
                    <a href="search.php" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>

            <!-- Vans & Trucks -->
            <div class="col-lg-3 col-md-6">
                <div class="vehicle-category-card h-100">
                    <div class="vehicle-image mb-3">
                            <img src="assets/images/van.png" alt="Vans & Trucks" class="img-fluid rounded-3">
                    </div>
                    <h4 class="fw-bold">Vans & Trucks</h4>
                    <p class="text-muted mb-3">
                        You're looking for a SUV for your business or leisure trip?
                    </p>
                    <a href="search.php" class="see-more-link text-success fw-bold">See more →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features & Benefits Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 mb-5">
            <!-- Feature 1: Discounts -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-benefit-card text-center h-100">
                    <div class="feature-icon mb-4">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15 10h30v20H15z" stroke="#212529" stroke-width="2" fill="none"/>
                            <circle cx="25" cy="20" r="3" fill="#212529"/>
                            <circle cx="35" cy="20" r="3" fill="#212529"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-3">Discounts and benefits</h4>
                    <p class="text-muted mb-0">
                        Become a Privilege For You member
                    </p>
                    <button class="btn btn-warning mt-3 fw-bold w-100">Privilege For You Specific Terms</button>
                </div>
            </div>

            <!-- Feature 2: Weekly/Monthly -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-benefit-card text-center h-100">
                    <div class="feature-icon mb-4">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="10" y="15" width="40" height="30" stroke="#212529" stroke-width="2" fill="none" rx="2"/>
                            <line x1="15" y1="20" x2="45" y2="20" stroke="#212529" stroke-width="1"/>
                            <line x1="15" y1="25" x2="45" y2="25" stroke="#212529" stroke-width="1"/>
                            <circle cx="20" cy="35" r="2" fill="#212529"/>
                            <circle cx="30" cy="35" r="2" fill="#212529"/>
                            <circle cx="40" cy="35" r="2" fill="#212529"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-3">Daily, weekly or monthly</h4>
                    <p class="text-muted mb-0">
                        Rent a car as long as you need
                    </p>
                    <button class="btn btn-warning mt-3 fw-bold w-100">Book now</button>
                </div>
            </div>

            <!-- Feature 3: Online Check-in -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-benefit-card text-center h-100">
                    <div class="feature-icon mb-4">
                        <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="15" y="20" width="30" height="25" stroke="#212529" stroke-width="2" fill="none" rx="2"/>
                            <line x1="30" y1="25" x2="30" y2="35" stroke="#212529" stroke-width="2"/>
                            <line x1="25" y1="30" x2="35" y2="30" stroke="#212529" stroke-width="2"/>
                        </svg>
                    </div>
                    <h4 class="fw-bold mb-3">Online check-in</h4>
                    <p class="text-muted mb-0">
                        Get on the road as fast as possible
                    </p>
                    <button class="btn btn-warning mt-3 fw-bold w-100">Check in now</button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

