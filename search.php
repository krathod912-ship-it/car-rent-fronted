<?php
$pageTitle = 'Search & Filter';
include __DIR__ . '/partials/header.php';

$pdo = require __DIR__ . '/admin/db.php';
$vehicles = [];
if ($pdo) {
    $stmt = $pdo->query("SELECT id, name, category, transmission, fuel, seats, price_per_day, image_path FROM vehicles WHERE is_active = 1 ORDER BY category, price_per_day");
    $vehicles = $stmt->fetchAll();
}

$typeFilter = $_GET['type'] ?? '';
$typeFilter = in_array($typeFilter, ['city', 'family', 'luxury'], true) ? $typeFilter : '';
if ($typeFilter) {
    $vehicles = array_values(array_filter($vehicles, static fn ($v) => ($v['category'] ?? '') === $typeFilter));
}

$categoryBadge = [
    'family' => ['label' => 'Family Car', 'class' => 'success'],
    'city' => ['label' => 'City Car', 'class' => 'info'],
    'luxury' => ['label' => 'Luxury Car', 'class' => 'warning'],
];

function formatInr(int $amount): string
{
    return '₹' . number_format($amount, 0, '.', ',');
}
?>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold mb-2">Available Cars</h2>
                        <p class="text-muted mb-0">
                            Choose from our premium selection of vehicles for your perfect journey.
                        </p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary small">
                        <?php echo count($vehicles); ?> cars found
                    </span>
                </div>

                <div class="row g-4" id="carList">
                    <?php if (!$pdo): ?>
                        <div class="col-12">
                            <div class="alert alert-danger">Database connection failed. Please check `car-rental/admin/db.php`.</div>
                        </div>
                    <?php elseif (empty($vehicles)): ?>
                        <div class="col-12">
                            <div class="alert alert-warning">
                                No vehicles found. Run `setup_users.php` once to seed vehicles.
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($vehicles as $v): ?>
                            <?php
                                $cat = $v['category'] ?? 'city';
                                $badge = $categoryBadge[$cat] ?? ['label' => 'Car', 'class' => 'secondary'];
                                $img = $v['image_path'] ?: 'assets/images/van.png';
                                $trans = ucfirst((string) ($v['transmission'] ?? ''));
                                $fuel = ucfirst((string) ($v['fuel'] ?? ''));
                                $seats = (int) ($v['seats'] ?? 0);
                                $ppd = (int) ($v['price_per_day'] ?? 0);
                            ?>
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="car-image-container" style="height: 250px; overflow: hidden; background: #f8f9fa;">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($v['name']); ?>" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <span class="badge bg-<?php echo $badge['class']; ?> mb-2"><?php echo htmlspecialchars($badge['label']); ?></span>
                                        <h6 class="card-title mb-1"><?php echo htmlspecialchars($v['name']); ?></h6>
                                        <p class="small text-muted mb-2"><?php echo htmlspecialchars(trim("{$trans} · {$fuel} · {$seats} Seats")); ?></p>
                                        <p class="fw-bold text-primary mb-2"><?php echo htmlspecialchars(formatInr($ppd)); ?> / day</p>
                                        <a class="btn btn-outline-primary btn-sm" href="booking.php?vehicle_id=<?php echo (int) $v['id']; ?>">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

