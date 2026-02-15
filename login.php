<?php
$pageTitle = 'Login';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3 text-center">Login</h3>
                        <p class="text-muted small text-center mb-4">
                            Login to manage your bookings and continue your journey with CarRent.
                        </p>
                        <!-- Form with jQuery validation -->
                        <form id="loginForm" method="get" action="user-dashboard.php" novalidate>
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="loginEmail"
                                    placeholder="you@example.com"
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
                                    />
                                    <label class="form-check-label" for="rememberMe">
                                        Remember me
                                    </label>
                                </div>
                                <a href="#" class="small">Forgot password?</a>
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

