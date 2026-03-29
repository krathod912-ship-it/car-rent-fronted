<?php
require_once __DIR__ . '/user-auth.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = 'All fields are required.';
    } elseif (strlen($newPassword) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'New password and confirmation do not match.';
    } elseif ($newPassword === $currentPassword) {
        $error = 'New password must be different from current password.';
    } else {
        $pdo = require __DIR__ . '/admin/db.php';
        if (!$pdo) {
            $error = 'Unable to process request. Please try again later.';
        } else {
            $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id LIMIT 1');
            $stmt->execute([':id' => $_SESSION['user_id']]);
            $user = $stmt->fetch();
            if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                $error = 'Current password is incorrect.';
            } else {
                $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
                $update->execute([':hash' => $hash, ':id' => $_SESSION['user_id']]);
                $success = 'Your password has been changed successfully.';
            }
        }
    }
}

$pageTitle = 'Change Password';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3 text-center">Change Password</h3>
                        <p class="text-muted small text-center mb-4">
                            Enter your current password and choose a new one.
                        </p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                            <div class="text-center">
                                <a href="user-dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                            </div>
                        <?php else: ?>
                        <form method="post" action="change-password.php" novalidate>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required minlength="6" />
                                <div class="invalid-feedback">Please enter your current password.</div>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6" />
                                <div class="invalid-feedback">Password must be at least 6 characters.</div>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6" />
                                <div class="invalid-feedback">Please confirm your new password.</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Change Password</button>
                                <a href="user-dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
