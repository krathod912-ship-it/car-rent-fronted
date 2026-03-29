<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: user-dashboard.php');
    exit;
}

$error = '';
$success = '';

function ensureUsersTableForRegistration(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            phone VARCHAR(20) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            address TEXT NULL,
            license_no VARCHAR(100) NULL,
            profile_image VARCHAR(255) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = preg_replace('/\D/', '', $_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $terms = !empty($_POST['terms']);

    if (strlen($firstName) < 2 || strlen($lastName) < 2) {
        $error = 'First and last name must be at least 2 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($phone) !== 10) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (!$terms) {
        $error = 'You must agree to the terms and conditions.';
    } else {
        $pdo = require __DIR__ . '/admin/db.php';
        if (!$pdo) {
            $error = 'Registration is temporarily unavailable. Please try again later.';
        } else {
            try {
                $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                $stmt->execute([':email' => $email]);
                if ($stmt->fetch()) {
                    $error = 'An account with this email already exists.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $insert = $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, password_hash) VALUES (:fn, :ln, :email, :phone, :hash)');
                    $insert->execute([
                        ':fn' => $firstName,
                        ':ln' => $lastName,
                        ':email' => $email,
                        ':phone' => $phone,
                        ':hash' => $hash,
                    ]);
                    $newUserId = (int) $pdo->lastInsertId();

                    // Auto-login newly registered user and redirect to profile page.
                    session_regenerate_id(true);
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_id'] = $newUserId;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
                    $_SESSION['user_phone'] = $phone;
                    $_SESSION['user_address'] = '';
                    $_SESSION['user_license_no'] = '';
                    $_SESSION['user_profile_image'] = '';

                    header('Location: edit-profile.php?welcome=1');
                    exit;
                }
            } catch (PDOException $e) {
                error_log('Registration error: ' . $e->getMessage());
                $missingTable = strpos($e->getMessage(), "doesn't exist") !== false;
                if ($missingTable) {
                    try {
                        ensureUsersTableForRegistration($pdo);

                        // Retry registration once after self-healing table setup.
                        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                        $stmt->execute([':email' => $email]);
                        if ($stmt->fetch()) {
                            $error = 'An account with this email already exists.';
                        } else {
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                            $insert = $pdo->prepare('INSERT INTO users (first_name, last_name, email, phone, password_hash) VALUES (:fn, :ln, :email, :phone, :hash)');
                            $insert->execute([
                                ':fn' => $firstName,
                                ':ln' => $lastName,
                                ':email' => $email,
                                ':phone' => $phone,
                                ':hash' => $hash,
                            ]);

                            $newUserId = (int) $pdo->lastInsertId();
                            session_regenerate_id(true);
                            $_SESSION['user_logged_in'] = true;
                            $_SESSION['user_id'] = $newUserId;
                            $_SESSION['user_email'] = $email;
                            $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
                            $_SESSION['user_phone'] = $phone;
                            $_SESSION['user_address'] = '';
                            $_SESSION['user_license_no'] = '';
                            $_SESSION['user_profile_image'] = '';
                            header('Location: edit-profile.php?welcome=1');
                            exit;
                        }
                    } catch (PDOException $setupError) {
                        error_log('Registration setup error: ' . $setupError->getMessage());
                        $error = 'Registration setup failed. Please run setup_users.php once.';
                    }
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    }
}

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
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php else: ?>
                        <form id="registerForm" method="post" action="register.php" novalidate>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label for="firstName" class="form-label">First name</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="firstName"
                                        name="first_name"
                                        value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
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
                                        name="last_name"
                                        value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
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
                                    name="email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
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
                                    name="phone"
                                    pattern="[0-9]{10}"
                                    placeholder="10-digit number"
                                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
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
                                        name="password"
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
                                        name="confirm_password"
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
                                    value="1"
                                    id="termsRegister"
                                    name="terms"
                                    <?php echo !empty($_POST['terms']) ? 'checked' : ''; ?>
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
                        </form>
                        <?php endif; ?>
                        <p class="small text-center mb-0 mt-3">
                            Already have an account?
                            <a href="login.php">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
