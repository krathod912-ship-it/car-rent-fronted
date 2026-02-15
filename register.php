<?php
$pageTitle = 'Register';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3 text-center">Create Account</h3>
                        <p class="text-muted small text-center mb-4">
                            Sign up now and enjoy fast, secure, and convenient car reservations.
                        </p>
                        <!-- Registration form with jQuery validation -->
                        <form id="registerForm" method="get" action="login.php" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">First name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="firstName"
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Please provide your first name.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="lastName" class="form-label">Last name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="lastName"
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Please provide your last name.
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="regEmail" class="form-label">Email address</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="regEmail"
                                    required
                                />
                                <div class="invalid-feedback">
                                    Please enter a valid email address.
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="phone" class="form-label">Mobile number</label>
                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    pattern="[0-9]{10}"
                                    placeholder="10-digit number"
                                    required
                                />
                                <div class="invalid-feedback">
                                    Please enter a valid 10-digit mobile number.
                                </div>
                            </div>
                            <div class="row g-3 mt-1">
                                <div class="col-sm-6">
                                    <label for="regPassword" class="form-label">Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="regPassword"
                                        minlength="6"
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Password must be at least 6 characters.
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="confirmPassword"
                                        minlength="6"
                                        required
                                    />
                                    <div class="invalid-feedback">
                                        Please confirm your password.
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mt-3">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value=""
                                    id="termsRegister"
                                    required
                                />
                                <label class="form-check-label small" for="termsRegister">
                                    I agree to the terms and conditions.
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                            <div class="d-grid mt-4 mb-3">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <p class="small text-center mb-0">
                                Already have an account?
                                <a href="login.php">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>

