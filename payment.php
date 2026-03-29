<?php
$requireLogin = true;
require_once __DIR__ . '/user-auth.php';
$pageTitle = 'Payment';
include __DIR__ . '/partials/header.php';

$pdo = require __DIR__ . '/admin/db.php';
$error = '';

$bookingId = (int) ($_GET['booking_id'] ?? $_POST['booking_id'] ?? 0);
$userId = (int) ($_SESSION['user_id'] ?? 0);
$booking = null;

if ($pdo && $bookingId > 0 && $userId > 0) {
    $stmt = $pdo->prepare("
        SELECT
            b.id AS booking_id,
            b.status,
            b.pickup_datetime,
            b.drop_datetime,
            v.id AS vehicle_id,
            v.name AS vehicle_name,
            v.price_per_day,
            v.image_path
        FROM bookings b
        JOIN vehicles v ON v.id = b.vehicle_id
        WHERE b.id = :bid AND b.user_id = :uid
        LIMIT 1
    ");
    $stmt->execute([':bid' => $bookingId, ':uid' => $userId]);
    $booking = $stmt->fetch();
}

if (!$pdo) {
    $error = 'Database connection failed. Please check admin/db.php.';
} elseif (!$booking) {
    $error = 'Booking not found. Please create a booking first.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $days = (int) ($_POST['days'] ?? 0);
    $method = (string) ($_POST['paymentMethod'] ?? '');
    $terms = !empty($_POST['termsCheck']);
    $allowedMethods = ['google_pay', 'phonepe', 'paytm'];

    if (($booking['status'] ?? '') === 'paid') {
        header('Location: user-dashboard.php?payment=already_paid');
        exit;
    }

    if (!$terms) {
        $error = 'Please accept the terms & conditions.';
    } elseif ($days < 1 || $days > 30) {
        $error = 'Please select rental duration (days).';
    } elseif (!in_array($method, $allowedMethods, true)) {
        $error = 'Please select a payment method.';
    } else {
        $pricePerDay = (int) ($booking['price_per_day'] ?? 0);
        $amount = $pricePerDay * $days;

        try {
            $pdo->beginTransaction();
            $ins = $pdo->prepare("INSERT INTO payments (booking_id, method, days, amount, status) VALUES (:bid,:method,:days,:amount,'success')");
            $ins->execute([
                ':bid' => $bookingId,
                ':method' => $method,
                ':days' => $days,
                ':amount' => $amount,
            ]);
            $upd = $pdo->prepare("UPDATE bookings SET status = 'paid' WHERE id = :bid AND user_id = :uid");
            $upd->execute([':bid' => $bookingId, ':uid' => $userId]);
            $pdo->commit();
            header('Location: user-dashboard.php?payment=success');
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log('Payment error: ' . $e->getMessage());
            $error = 'Payment failed. Please try again.';
        }
    }
}

$vehicleName = $booking['vehicle_name'] ?? 'Unknown Car';
$pricePerDay = (int) ($booking['price_per_day'] ?? 0);
$vehicleImage = $booking['image_path'] ?? 'assets/images/van.png';
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <!-- Payment Form -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Complete Your Payment</h3>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post" action="payment.php" novalidate>
                            <input type="hidden" name="booking_id" value="<?php echo (int) $bookingId; ?>" />
                        <!-- Car Summary -->
                        <div class="card mb-4 bg-primary-subtle border-0">
                            <div class="row g-0 align-items-center">
                                <div class="col-md-3">
                                    <div style="height: 150px; overflow: hidden; background: #f8f9fa;">
                                        <img id="carImage" src="<?php echo htmlspecialchars($vehicleImage); ?>" alt="Car" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3">Selected Vehicle</h5>
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <p class="mb-1"><strong id="carName"><?php echo htmlspecialchars($vehicleName); ?></strong></p>
                                                <p class="text-muted mb-0" id="carPrice">Price will be calculated</p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <p class="mb-0 text-muted small">Price per Day</p>
                                                <p class="fw-bold text-primary" id="pricePerDay">₹<?php echo number_format($pricePerDay, 0, '.', ','); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rental Duration Selection -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Select Rental Duration</h5>
                            <p class="text-muted small mb-3">Choose how many days you want to rent the car</p>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days1" value="1" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days1">
                                        <strong>1 Day</strong>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days2" value="2" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days2">
                                        <strong>2 Days</strong>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days3" value="3" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days3">
                                        <strong>3 Days</strong>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days4" value="4" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days4">
                                        <strong>4 Days</strong>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days5" value="5" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days5">
                                        <strong>5 Days</strong>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="days" id="days6" value="6" onchange="calculateTotal()">
                                    <label class="btn btn-outline-primary w-100" for="days6">
                                        <strong>6 Days</strong>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Breakdown -->
                        <div class="card border-2 border-primary mb-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Payment Breakdown</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Price per Day</span>
                                    <span id="breakdownDaily">₹0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Number of Days</span>
                                    <span id="breakdownDays">0</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total Amount</span>
                                    <span class="fw-bold text-primary fs-5" id="totalAmount">₹0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Select Payment Method</h5>
                            <p class="text-muted small mb-3">Choose your preferred UPI payment method</p>

                            <div class="payment-methods">
                                <!-- UPI Method 1 -->
                                <div class="mb-3">
                                    <input type="radio" class="btn-check" name="paymentMethod" id="upi1" value="google_pay">
                                    <label class="btn btn-outline-secondary w-100 text-start p-3 border-2" for="upi1">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg width="32" height="32" fill="#4285F4" viewBox="0 0 24 24">
                                                    <path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm0 22c-5.52 0-10-4.48-10-10S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">Google Pay (UPI)</strong>
                                                <small class="text-muted">Fast & Secure Payment</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- UPI Method 2 -->
                                <div class="mb-3">
                                    <input type="radio" class="btn-check" name="paymentMethod" id="upi2" value="phonepe">
                                    <label class="btn btn-outline-secondary w-100 text-start p-3 border-2" for="upi2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg width="32" height="32" fill="#6B45FF" viewBox="0 0 24 24">
                                                    <path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm0 22c-5.52 0-10-4.48-10-10S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">PhonePe (UPI)</strong>
                                                <small class="text-muted">Instant Money Transfer</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- UPI Method 3 -->
                                <div class="mb-3">
                                    <input type="radio" class="btn-check" name="paymentMethod" id="upi3" value="paytm">
                                    <label class="btn btn-outline-secondary w-100 text-start p-3 border-2" for="upi3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <svg width="32" height="32" fill="#002970" viewBox="0 0 24 24">
                                                    <path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm0 22c-5.52 0-10-4.48-10-10S6.48 2 12 2s10 4.48 10 10-4.48 10-10 10z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-grow-1">
                                                <strong class="d-block">Paytm UPI</strong>
                                                <small class="text-muted">Secure & Reliable</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" value="1">
                                <label class="form-check-label small" for="termsCheck">
                                    I agree to the <a href="#" class="text-decoration-none">Terms & Conditions</a> and <a href="#" class="text-decoration-none">Rental Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1" id="paymentBtn" disabled>
                                Proceed to Payment
                            </button>
                            <a href="search.php" class="btn btn-outline-secondary btn-lg">Back</a>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="margin-top: 20px;">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="fw-bold mb-0">📋 Order Summary</h5>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-4 pb-4 border-bottom">
                            <p class="text-muted small mb-2">🚗 Car Selected</p>
                            <p class="fw-bold fs-5" id="summaryCarName">No car selected</p>
                        </div>

                        <div class="mb-4 pb-4 border-bottom">
                            <p class="text-muted small mb-2">📅 Rental Duration</p>
                            <p class="fw-bold fs-5" id="summaryDays">Not selected yet</p>
                        </div>

                        <div class="mb-4 pb-4 border-bottom">
                            <p class="text-muted small mb-2">💰 Daily Rate</p>
                            <p class="fw-bold fs-5" id="summaryRate">₹0</p>
                        </div>

                        <div class="bg-primary-subtle p-3 rounded mb-3 border border-primary">
                            <p class="text-muted small mb-2">Total Amount to Pay</p>
                            <p class="fw-bold fs-3 text-primary mb-0" id="summaryTotal">₹0</p>
                        </div>

                        <div class="p-3 bg-success-subtle rounded border border-success-50 mb-2">
                            <p class="mb-0 text-muted small">
                                <strong>✓ Secure Payment</strong><br>
                                All transactions are SSL encrypted
                            </p>
                        </div>

                        <div class="p-3 bg-info-subtle rounded border border-info-50">
                            <p class="mb-0 text-muted small">
                                <strong>ℹ Multiple UPI Options</strong><br>
                                Google Pay, PhonePe, Paytm
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carName = <?php echo json_encode($vehicleName); ?>;
    const pricePerDay = <?php echo (int) $pricePerDay; ?>;
    document.getElementById('summaryCarName').textContent = carName;
    document.getElementById('summaryRate').textContent = '₹' + pricePerDay.toLocaleString('en-IN');
    document.getElementById('breakdownDaily').textContent = '₹' + pricePerDay.toLocaleString('en-IN');
    window.pricePerDay = pricePerDay;

    // Enable payment button when terms are checked
    document.getElementById('termsCheck').addEventListener('change', function() {
        const daySelected = document.querySelector('input[name="days"]:checked');
        const methodSelected = document.querySelector('input[name="paymentMethod"]:checked');
        
        document.getElementById('paymentBtn').disabled = 
            !this.checked || !daySelected || !methodSelected;
    });

    // Enable/disable payment button on day selection
    document.querySelectorAll('input[name="days"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updatePaymentBtnStatus();
        });
    });

    // Enable/disable payment button on method selection
    document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updatePaymentBtnStatus();
        });
    });
});

function calculateTotal() {
    const days = parseInt(document.querySelector('input[name="days"]:checked')?.value) || 0;
    const total = window.pricePerDay * days;

    // Update breakdown
    document.getElementById('breakdownDays').textContent = days;
    document.getElementById('totalAmount').textContent = '₹' + total.toLocaleString('en-IN');

    // Update summary
    document.getElementById('summaryDays').textContent = days + ' day' + (days !== 1 ? 's' : '');
    document.getElementById('summaryTotal').textContent = '₹' + total.toLocaleString('en-IN');

    updatePaymentBtnStatus();
}

function updatePaymentBtnStatus() {
    const daySelected = document.querySelector('input[name="days"]:checked');
    const methodSelected = document.querySelector('input[name="paymentMethod"]:checked');
    const termsChecked = document.getElementById('termsCheck').checked;

    document.getElementById('paymentBtn').disabled = 
        !daySelected || !methodSelected || !termsChecked;
}
</script>

<style>
.btn-check:checked + .btn {
    border-color: #0d6efd !important;
    background-color: #0d6efd !important;
    color: white !important;
}

.payment-methods .btn {
    transition: all 0.3s ease;
}

.payment-methods .btn:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
}

.sticky-top {
    z-index: 100;
}
</style>

<?php include __DIR__ . '/partials/footer.php'; ?>
