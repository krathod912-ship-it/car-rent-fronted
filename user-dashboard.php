<?php
require_once __DIR__ . '/user-auth.php';
$pageTitle = 'User Dashboard';
include __DIR__ . '/partials/header.php';

$userName = htmlspecialchars($_SESSION['user_name'] ?? 'User');
$userEmail = htmlspecialchars($_SESSION['user_email'] ?? '');
$userPhone = htmlspecialchars($_SESSION['user_phone'] ?? '');
$userAddress = htmlspecialchars($_SESSION['user_address'] ?? '');
$userProfileImage = htmlspecialchars($_SESSION['user_profile_image'] ?? '');

$pdo = require __DIR__ . '/admin/db.php';
$userId = (int) ($_SESSION['user_id'] ?? 0);
$bookings = [];
if ($pdo && $userId > 0) {
    $stmt = $pdo->prepare("
        SELECT
            b.id,
            b.pickup_datetime,
            b.drop_datetime,
            b.status,
            v.name AS vehicle_name
        FROM bookings b
        JOIN vehicles v ON v.id = b.vehicle_id
        WHERE b.user_id = :uid
        ORDER BY b.id DESC
        LIMIT 10
    ");
    $stmt->execute([':uid' => $userId]);
    $bookings = $stmt->fetchAll();
}

function fmtDateRange(?string $start, ?string $end): string
{
    $s = $start ? date('d M Y, H:i', strtotime($start)) : '';
    $e = $end ? date('d M Y, H:i', strtotime($end)) : '';
    return trim($s . ' - ' . $e);
}
?>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-3">Welcome, <?php echo $userName; ?></h2>
        <p class="text-muted small mb-4">
            Manage your bookings, view your profile, and continue your journey with CarRent.
        </p>
        <?php if (isset($_GET['payment']) && $_GET['payment'] === 'success'): ?>
            <div class="alert alert-success">Payment successful. Your booking is confirmed.</div>
        <?php elseif (isset($_GET['payment']) && $_GET['payment'] === 'already_paid'): ?>
            <div class="alert alert-info">This booking was already paid.</div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase small mb-1">Active Bookings</h6>
                        <?php
                            $activeCount = 0;
                            foreach ($bookings as $b) {
                                if (($b['status'] ?? '') !== 'cancelled') {
                                    $activeCount++;
                                }
                            }
                        ?>
                        <h3 class="fw-bold"><?php echo (int) $activeCount; ?></h3>
                        <a href="search.php" class="btn btn-primary btn-sm mt-2">New Booking</a>
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
                                    <?php if (!$pdo): ?>
                                        <tr>
                                            <td colspan="4" class="p-3">
                                                <span class="text-danger small">Database connection failed. Check `admin/db.php`.</span>
                                            </td>
                                        </tr>
                                    <?php elseif (empty($bookings)): ?>
                                        <tr>
                                            <td colspan="4" class="p-3">
                                                <span class="text-muted small">No bookings yet. Start by booking a car from the Cars page.</span>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($bookings as $b): ?>
                                            <?php
                                                $status = $b['status'] ?? 'pending';
                                                $badgeMap = [
                                                    'pending' => ['Pending', 'warning'],
                                                    'paid' => ['Paid', 'success'],
                                                    'cancelled' => ['Cancelled', 'danger'],
                                                ];
                                                $badge = $badgeMap[$status] ?? [$status, 'secondary'];
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($b['vehicle_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars(fmtDateRange($b['pickup_datetime'] ?? null, $b['drop_datetime'] ?? null)); ?></td>
                                                <td><span class="badge bg-<?php echo $badge[1]; ?>-subtle text-<?php echo $badge[1]; ?>"><?php echo htmlspecialchars($badge[0]); ?></span></td>
                                                <td>
                                                    <?php if ($status === 'pending'): ?>
                                                        <a class="btn btn-outline-primary btn-sm" href="payment.php?booking_id=<?php echo (int) $b['id']; ?>">Pay</a>
                                                    <?php else: ?>
                                                        <button class="btn btn-outline-secondary btn-sm" type="button" disabled>View</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
                <!-- User Profile Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; font-size: 1.1rem;">
                            <?php if ($userProfileImage): ?>
                                <img src="<?php echo $userProfileImage; ?>" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" />
                            <?php else: ?>
                                <i class="bi bi-person-fill"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h6 class="mb-0">My Profile</h6>
                            <small class="text-muted">Account settings</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 small">
                            <strong><?php echo $userName; ?></strong><br />
                            <span class="text-muted"><?php echo $userEmail; ?></span>
                            <?php if ($userPhone): ?><br /><span class="text-muted"><i class="bi bi-telephone me-1"></i><?php echo $userPhone; ?></span><?php endif; ?>
                            <?php if ($userAddress): ?><br /><span class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo $userAddress; ?></span><?php endif; ?>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="edit-profile.php" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                            <a href="change-password.php" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                            <a href="user-logout.php" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to logout?');">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

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

