<?php
$pageTitle = 'Booking';
include __DIR__ . '/partials/header.php';

$pdo = require __DIR__ . '/admin/db.php';
$vehicleId = (int) ($_GET['vehicle_id'] ?? 0);
$vehicle = null;
if ($pdo && $vehicleId > 0) {
    $stmt = $pdo->prepare("SELECT id, name, price_per_day FROM vehicles WHERE id = :id AND is_active = 1 LIMIT 1");
    $stmt->execute([':id' => $vehicleId]);
    $vehicle = $stmt->fetch();
}

$error = '';

// Create booking (dynamic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true || empty($_SESSION['user_id'])) {
        header('Location: login.php?redirect=' . urlencode('booking.php?vehicle_id=' . (int)($_POST['vehicle_id'] ?? 0)));
        exit;
    }

    $userId = (int) $_SESSION['user_id'];
    $vehicleIdPost = (int) ($_POST['vehicle_id'] ?? 0);
    $pickupLocation = trim($_POST['pickup_location'] ?? '');
    $dropLocation = trim($_POST['drop_location'] ?? '');
    $pickupDatetime = trim($_POST['pickup_datetime'] ?? '');
    $dropDatetime = trim($_POST['drop_datetime'] ?? '');
    $pickupType = ($_POST['pickup_type'] ?? '') === 'with_driver' ? 'with_driver' : 'self_drive';
    $fullName = trim($_POST['full_name'] ?? '');
    $phone = preg_replace('/\D/', '', $_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $licenseNo = trim($_POST['license_no'] ?? '');

    if (!$pdo) {
        $error = 'Database connection failed. Please check admin/db.php.';
    } elseif ($vehicleIdPost <= 0) {
        $error = 'Please select a vehicle first.';
    } elseif ($pickupLocation === '' || $dropLocation === '' || $pickupDatetime === '' || $dropDatetime === '') {
        $error = 'Please fill pickup/drop locations and dates.';
    } elseif (strlen($fullName) < 3) {
        $error = 'Please enter your full name.';
    } elseif (strlen($phone) !== 10) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } elseif (strlen($licenseNo) < 5) {
        $error = 'Please enter your license number.';
    } else {
        // Ensure vehicle exists
        $stmt = $pdo->prepare("SELECT id FROM vehicles WHERE id = :id AND is_active = 1 LIMIT 1");
        $stmt->execute([':id' => $vehicleIdPost]);
        if (!$stmt->fetch()) {
            $error = 'Selected vehicle not found.';
        } else {
            try {
                $ins = $pdo->prepare("
                    INSERT INTO bookings
                        (user_id, vehicle_id, pickup_location, drop_location, pickup_datetime, drop_datetime, pickup_type,
                         customer_full_name, customer_phone, customer_email, customer_license_no, status)
                    VALUES
                        (:user_id, :vehicle_id, :pickup_location, :drop_location, :pickup_datetime, :drop_datetime, :pickup_type,
                         :full_name, :phone, :email, :license_no, 'pending')
                ");
                $ins->execute([
                    ':user_id' => $userId,
                    ':vehicle_id' => $vehicleIdPost,
                    ':pickup_location' => $pickupLocation,
                    ':drop_location' => $dropLocation,
                    ':pickup_datetime' => date('Y-m-d H:i:s', strtotime($pickupDatetime)),
                    ':drop_datetime' => date('Y-m-d H:i:s', strtotime($dropDatetime)),
                    ':pickup_type' => $pickupType,
                    ':full_name' => $fullName,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':license_no' => $licenseNo,
                ]);
                $bookingId = (int) $pdo->lastInsertId();
                header('Location: payment.php?booking_id=' . $bookingId);
                exit;
            } catch (PDOException $e) {
                error_log('Booking insert error: ' . $e->getMessage());
                $error = 'Could not create booking. Please try again.';
            }
        }
    }
}
?>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            
            <div class="col-12">
                <h2 class="fw-bold mb-3">Book Your Car</h2>
                <p class="text-muted small mb-4">
                    Book your preferred vehicle quickly and securely in just a few simple steps.
                </p>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <!-- Booking form with jQuery validation -->
                <form id="bookingForm" method="post" action="booking.php" novalidate>
                    <input type="hidden" name="vehicle_id" value="<?php echo (int) ($vehicle['id'] ?? $vehicleId); ?>" />
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pickup Location</label>
                            <input type="text" class="form-control" name="pickup_location" placeholder="e.g., MG Road, Bangalore" required value="<?php echo htmlspecialchars($_POST['pickup_location'] ?? ''); ?>" />
                            <div class="invalid-feedback">Please enter pickup location.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Drop Location</label>
                            <input type="text" class="form-control" name="drop_location" placeholder="e.g., Whitefield, Bangalore" required value="<?php echo htmlspecialchars($_POST['drop_location'] ?? ''); ?>" />
                            <div class="invalid-feedback">Please enter drop location.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pickup Date &amp; Time</label>
                            <input type="datetime-local" class="form-control" name="pickup_datetime" required value="<?php echo htmlspecialchars($_POST['pickup_datetime'] ?? ''); ?>" />
                            <div class="invalid-feedback">Please select pickup date &amp; time.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Drop Date &amp; Time</label>
                            <input type="datetime-local" class="form-control" name="drop_datetime" required value="<?php echo htmlspecialchars($_POST['drop_datetime'] ?? ''); ?>" />
                            <div class="invalid-feedback">Please select drop date &amp; time.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Car</label>
                            <div class="card border-primary">
                                <div class="card-body text-center py-3">
                                    <?php if ($vehicle): ?>
                                        <p class="mb-1 fw-bold"><?php echo htmlspecialchars($vehicle['name']); ?></p>
                                        <p class="mb-2 text-muted small">₹<?php echo number_format((int)$vehicle['price_per_day']); ?> / day</p>
                                        <a href="search.php" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-arrow-left"></i> Change Car
                                        </a>
                                    <?php else: ?>
                                        <p class="mb-3 text-muted">Please select a car first</p>
                                        <a href="search.php" class="btn btn-primary">
                                            <i class="bi bi-car-front"></i> View All Cars
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pickup Type</label>
                            <select class="form-select" name="pickup_type" required>
                                <option value="">Choose...</option>
                                <option value="self_drive" <?php echo (($_POST['pickup_type'] ?? '') === 'self_drive') ? 'selected' : ''; ?>>Self Drive</option>
                                <option value="with_driver" <?php echo (($_POST['pickup_type'] ?? '') === 'with_driver') ? 'selected' : ''; ?>>With Driver</option>
                            </select>
                            <div class="invalid-feedback">Please choose pickup type.</div>
                        </div>
                    </div>

                    <hr class="my-4" />

                    <h5 class="mb-3">Personal Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" placeholder="e.g., John Doe" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ($_SESSION['user_name'] ?? '')); ?>" />
                            <div class="invalid-feedback">Please enter your name.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input
                                type="tel"
                                class="form-control"
                                name="phone"
                                pattern="[0-9]{10}"
                                placeholder="10-digit number"
                                required
                                value="<?php echo htmlspecialchars($_POST['phone'] ?? ($_SESSION['user_phone'] ?? '')); ?>"
                            />
                            <div class="invalid-feedback">Please enter a valid mobile number.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ($_SESSION['user_email'] ?? '')); ?>" />
                            <div class="invalid-feedback">Please enter a valid email.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Driving License No.</label>
                            <input type="text" class="form-control" name="license_no" placeholder="DL-0000XX..." required value="<?php echo htmlspecialchars($_POST['license_no'] ?? ($_SESSION['user_license_no'] ?? '')); ?>" />
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
        
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

