<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: user-dashboard.php');
    exit;
}

$error = '';
$redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'user-dashboard.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter email and password.';
    } else {
        // Accept any login credentials (no real auth check)
        session_regenerate_id(true);
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = 0;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = trim(explode('@', $email)[0]) ?: 'User';
        $_SESSION['user_login_time'] = time();
        header('Location: ' . (filter_var($redirect, FILTER_SANITIZE_URL) ?: 'user-dashboard.php'));
        exit;
    }
}

$pageTitle = 'Login';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
                    <div class="alert alert-success">You have been logged out successfully.</div>
                <?php endif; ?>
                <?php if (isset($_GET['expired'])): ?>
                    <div class="alert alert-warning">Your session expired. Please login again.</div>
                <?php endif; ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3 text-center">Login</h3>
                        <p class="text-muted small text-center mb-4">
                            Login to manage your bookings and continue your journey with CarRent.
                        </p>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <form id="loginForm" method="post" action="login.php" novalidate>
                            <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>" />
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="loginEmail"
                                    name="email"
                                    placeholder="you@example.com"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                    required
                                />
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="loginPassword"
                                    name="password"
                                    minlength="6"
                                    required
                                />
                                <div class="invalid-feedback">
                                    Please enter your password (minimum 6 characters).
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="rememberMe"
                                        name="remember"
                                    />
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="forgotpass.php" class="small">Forgot password?</a>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                            <p class="small text-center mb-0">
                                New user?
                                <a href="register.php">Create an account</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
