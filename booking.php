<?php
$pageTitle = 'Booking';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <h2 class="fw-bold mb-3">Book Your Car</h2>
                <p class="text-muted small mb-4">
                    Book your preferred vehicle quickly and securely in just a few simple steps.
                </p>
                <!-- Booking form with jQuery validation -->
                <form id="bookingForm" method="get" action="user-dashboard.php" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Location</label>
                            <input type="text" class="form-control" placeholder="e.g., New York" required />
                            <div class="invalid-feedback">Please enter pickup location.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Drop Location</label>
                            <input type="text" class="form-control" placeholder="e.g., New York" required />
                            <div class="invalid-feedback">Please enter drop location.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pickup Date &amp; Time</label>
                            <input type="datetime-local" class="form-control" required />
                            <div class="invalid-feedback">Please select pickup date &amp; time.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Drop Date &amp; Time</label>
                            <input type="datetime-local" class="form-control" required />
                            <div class="invalid-feedback">Please select drop date &amp; time.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Car</label>
                            <select class="form-select" required>
                                <option value="">Choose...</option>
                                <option>Hyundai i20 · Manual</option>
                                <option>Tesla Model 3 · Automatic</option>
                                <option>Maruti Swift · Manual</option>
                            </select>
                            <div class="invalid-feedback">Please choose a car.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pickup Type</label>
                            <select class="form-select" required>
                                <option value="">Choose...</option>
                                <option>Self Drive</option>
                                <option>With Driver</option>
                            </select>
                            <div class="invalid-feedback">Please choose pickup type.</div>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <h5 class="mb-3">Personal Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" placeholder="e.g., John Doe" required />
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input
                                type="tel"
                                class="form-control"
                                pattern="[0-9]{10}"
                                placeholder="10-digit number"
                                required
                            />
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required />
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driving License No.</label>
                            <input type="text" class="form-control" placeholder="DL-0000XX..." required />
                            <div class="invalid-feedback">Please enter your license number.</div>
                        </div>
                    </div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="termsBooking" required />
                        <label class="form-check-label small" for="termsBooking">
                            I confirm that the above details are correct.
                        </label>
                        <div class="invalid-feedback">Please confirm your details.</div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Booking Summary</h5>
                        <ul class="list-unstyled small mb-0">
                            <li class="d-flex justify-content-between">
                                <span>Estimated Fare</span>
                                <strong>₹4,499</strong>
                            </li>
                            <li class="d-flex justify-content-between text-success mt-2">
                                <span>Coupon Discount (if any)</span>
                                <strong>- ₹500</strong>
                            </li>
                            <li class="d-flex justify-content-between border-top pt-2 mt-2">
                                <span>Total Payable</span>
                                <strong>₹3,999</strong>
                            </li>
                        </ul>
                        <p class="small text-muted mt-3 mb-0">
                            The final amount includes all applicable charges and discounts.
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="fw-bold">Need Help?</h6>
                        <p class="small text-muted mb-2">
                            Use the floating chat button (bottom-right) to see a mock support chat UI.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

