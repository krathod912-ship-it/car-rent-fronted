<?php
$pageTitle = 'User Dashboard';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-3">Welcome, User</h2>
        <p class="text-muted small mb-4">
            This user area appears after login (frontend simulation). It shows bookings, ratings and quick links.
        </p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Active Bookings</h6>
                        <h3 class="fw-bold">2</h3>
                        <a href="booking.php" class="btn btn-primary btn-sm mt-2">New Booking</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Completed Trips</h6>
                        <h3 class="fw-bold">5</h3>
                        <p class="small text-muted mb-0">You can rate &amp; review completed trips.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Average Rating</h6>
                        <h3 class="fw-bold">4.8 / 5</h3>
                        <p class="small text-muted mb-0">Thank you for your feedback!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0">My Recent Bookings</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Car</th>
                                        <th>Dates</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hyundai Creta</td>
                                        <td>28 Jan - 30 Jan</td>
                                        <td><span class="badge bg-success-subtle text-success">Upcoming</span></td>
                                        <td><button class="btn btn-outline-primary btn-sm">View</button></td>
                                    </tr>
                                    <tr>
                                        <td>Maruti Swift</td>
                                        <td>10 Jan - 11 Jan</td>
                                        <td><span class="badge bg-secondary-subtle text-secondary">Completed</span></td>
                                        <td><button class="btn btn-outline-primary btn-sm">Rate</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0">Rate &amp; Review a Car</h6>
                    </div>
                    <div class="card-body">
                        <!-- HTML-only rating & review form (no JavaScript) -->
                        <form class="small">
                            <div class="mb-2">
                                <label class="form-label">Select Booking</label>
                                <select class="form-select" required>
                                    <option value="">Choose a completed trip...</option>
                                    <option>Swift · 10 Jan - 11 Jan</option>
                                    <option>Creta · 02 Jan - 03 Jan</option>
                                </select>
                                <div class="invalid-feedback">Please select a booking.</div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label d-block">Rating</label>
                                <!-- Simple radio buttons for rating (HTML only) -->
                                <div class="d-flex gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" id="rating1" required />
                                        <label class="form-check-label" for="rating1">1</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" id="rating2" />
                                        <label class="form-check-label" for="rating2">2</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" id="rating3" />
                                        <label class="form-check-label" for="rating3">3</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" id="rating4" />
                                        <label class="form-check-label" for="rating4">4</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" id="rating5" />
                                        <label class="form-check-label" for="rating5">5</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Review</label>
                                <textarea
                                    class="form-control"
                                    rows="3"
                                    placeholder="Share your experience..."
                                    required
                                ></textarea>
                                <div class="invalid-feedback">Please write a short review.</div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mt-1">
                                Submit Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0">Apply Coupon (UI Only)</h6>
                    </div>
                    <div class="card-body small">
                        <p class="text-muted mb-2">
                            Coupon UI only (no JavaScript). In the final version, backend/PHP will apply discounts.
                        </p>
                        <form id="couponForm">
                            <div class="input-group mb-2">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="couponCode"
                                    placeholder="Enter coupon code"
                                />
                                <button class="btn btn-outline-primary" type="button" id="applyCoupon">
                                    Apply
                                </button>
                            </div>
                            <p class="mb-0" id="couponMessage"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

