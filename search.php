<?php
$pageTitle = 'Search & Filter';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3">
                <h5 class="fw-bold mb-3">Filters</h5>
                <!-- Search form with jQuery validation -->
                <form id="searchForm" class="small" method="get" action="search.php" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Search by name</label>
                        <input
                            type="text"
                            class="form-control"
                            id="searchInput"
                            placeholder="e.g., Swift, Tesla"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price per day</label>
                        <select class="form-select" id="priceFilter">
                            <option value="">Any</option>
                            <option value="low">Below ₹2,000</option>
                            <option value="mid">₹2,000 - ₹4,000</option>
                            <option value="high">Above ₹4,000</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transmission</label>
                        <select class="form-select" id="transmissionFilter">
                            <option value="">Any</option>
                            <option value="manual">Manual</option>
                            <option value="automatic">Automatic</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fuel Type</label>
                        <select class="form-select" id="fuelFilter">
                            <option value="">Any</option>
                            <option value="petrol">Petrol</option>
                            <option value="diesel">Diesel</option>
                            <option value="electric">Electric</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">Apply Filters</button>
                    <button type="reset" class="btn btn-outline-secondary w-100">Clear</button>
                </form>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="fw-bold mb-0">Available Cars</h4>
                        <p class="text-muted small mb-0">
                            Find the perfect vehicle for your trip by selecting your preferences below.
                        </p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary small">
                        3 cars found
                    </span>
                </div>

                <div class="row g-4" id="carList">
                    <!-- Car 1 -->
                    <div
                        class="col-md-4 car-card"
                        data-name="swift"
                        data-price="low"
                        data-transmission="manual"
                        data-fuel="petrol"
                    >
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Maruti Swift</h6>
                                <p class="small text-muted mb-2">Manual · Petrol · 5 Seats</p>
                                <p class="fw-bold text-primary mb-2">₹1,499 / day</p>
                                <button class="btn btn-outline-primary btn-sm">Book Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Car 2 -->
                    <div
                        class="col-md-4 car-card"
                        data-name="creta"
                        data-price="mid"
                        data-transmission="automatic"
                        data-fuel="diesel"
                    >
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Hyundai Creta</h6>
                                <p class="small text-muted mb-2">Automatic · Diesel · 5 Seats</p>
                                <p class="fw-bold text-primary mb-2">₹2,999 / day</p>
                                <button class="btn btn-outline-primary btn-sm">Book Now</button>
                            </div>
                        </div>
                    </div>
                    <!-- Car 3 -->
                    <div
                        class="col-md-4 car-card"
                        data-name="tesla"
                        data-price="high"
                        data-transmission="automatic"
                        data-fuel="electric"
                    >
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="card-title mb-1">Tesla Model 3</h6>
                                <p class="small text-muted mb-2">Automatic · Electric · 5 Seats</p>
                                <p class="fw-bold text-primary mb-2">₹4,999 / day</p>
                                <button class="btn btn-outline-primary btn-sm">Book Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

