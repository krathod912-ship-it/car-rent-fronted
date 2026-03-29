<?php
require_once __DIR__ . '/user-auth.php';

$error = '';
$success = '';
$userId = (int) ($_SESSION['user_id'] ?? 0);
$pdo = $userId > 0 ? require __DIR__ . '/admin/db.php' : null;
$uploadWebDir = 'assets/uploads/profile';
$uploadDiskDir = __DIR__ . '/assets/uploads/profile';

if (!is_dir($uploadDiskDir)) {
    @mkdir($uploadDiskDir, 0775, true);
}

// Load user data: from DB if real user, else from session
if ($userId > 0 && $pdo) {
    $stmt = $pdo->prepare('SELECT first_name, last_name, email, phone, address, license_no, profile_image FROM users WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch();
    if (!$user) {
        header('Location: user-dashboard.php');
        exit;
    }
} else {
    // Session-only user (any-login mode)
    $email = $_SESSION['user_email'] ?? '';
    $name = $_SESSION['user_name'] ?? 'User';
    $parts = explode(' ', $name, 2);
    $user = [
        'first_name' => $parts[0] ?? 'User',
        'last_name' => $parts[1] ?? '',
        'email' => $email,
        'phone' => $_SESSION['user_phone'] ?? '',
        'address' => $_SESSION['user_address'] ?? '',
        'license_no' => $_SESSION['user_license_no'] ?? '',
        'profile_image' => $_SESSION['user_profile_image'] ?? '',
    ];
}

// Load session-only fields for DB users too
if ($userId > 0) {
    $user['address'] = $user['address'] ?? ($_SESSION['user_address'] ?? '');
    $user['license_no'] = $user['license_no'] ?? ($_SESSION['user_license_no'] ?? '');
    $user['profile_image'] = $user['profile_image'] ?? ($_SESSION['user_profile_image'] ?? '');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = preg_replace('/\D/', '', $_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $licenseNo = trim($_POST['license_no'] ?? '');
    $profileImagePath = $user['profile_image'] ?? '';

    if (!empty($_FILES['profile_image']['name'])) {
        if (!isset($_FILES['profile_image']['error']) || $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Profile image upload failed. Please try again.';
        } else {
            $tmpPath = $_FILES['profile_image']['tmp_name'];
            $fileSize = (int) ($_FILES['profile_image']['size'] ?? 0);
            $fileInfo = @getimagesize($tmpPath);
            $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
            $mime = $fileInfo['mime'] ?? '';

            if (!$fileInfo || !isset($allowed[$mime])) {
                $error = 'Only JPG, PNG, or WEBP images are allowed.';
            } elseif ($fileSize > 2 * 1024 * 1024) {
                $error = 'Profile image must be less than 2MB.';
            } else {
                $fileName = 'user_' . ($userId > 0 ? $userId : session_id()) . '_' . time() . '.' . $allowed[$mime];
                $targetPath = $uploadDiskDir . '/' . $fileName;
                if (!move_uploaded_file($tmpPath, $targetPath)) {
                    $error = 'Could not save uploaded profile image.';
                } else {
                    $profileImagePath = $uploadWebDir . '/' . $fileName;
                }
            }
        }
    }

    if (!$error && (strlen($firstName) < 2 || strlen($lastName) < 2)) {
        $error = 'First and last name must be at least 2 characters.';
    } elseif (!$error && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!$error && $phone !== '' && strlen($phone) !== 10) {
        $error = 'Please enter a valid 10-digit mobile number (or leave empty).';
    } else {
        if ($userId > 0 && $pdo) {
            // Update DB user
            try {
                $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1');
                $stmt->execute([':email' => $email, ':id' => $userId]);
                if ($stmt->fetch()) {
                    $error = 'This email is already used by another account.';
                } else {
                    $update = $pdo->prepare('UPDATE users SET first_name = :fn, last_name = :ln, email = :email, phone = :phone, address = :address, license_no = :license_no, profile_image = :profile_image WHERE id = :id');
                    $update->execute([
                        ':fn' => $firstName,
                        ':ln' => $lastName,
                        ':email' => $email,
                        ':phone' => $phone ?: ($user['phone'] ?? ''),
                        ':address' => $address,
                        ':license_no' => $licenseNo,
                        ':profile_image' => $profileImagePath,
                        ':id' => $userId,
                    ]);
                    $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_phone'] = $phone ?: ($user['phone'] ?? '');
                    $_SESSION['user_address'] = $address;
                    $_SESSION['user_license_no'] = $licenseNo;
                    $_SESSION['user_profile_image'] = $profileImagePath;
                    $success = 'Profile updated successfully.';
                    $user = ['first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'phone' => $phone ?: $user['phone'], 'address' => $address, 'license_no' => $licenseNo, 'profile_image' => $profileImagePath];
                }
            } catch (PDOException $e) {
                error_log('Edit profile error: ' . $e->getMessage());
                $error = 'Could not update profile. Please try again.';
            }
        } else {
            // Session-only user
            $_SESSION['user_name'] = trim($firstName . ' ' . $lastName);
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_address'] = $address;
            $_SESSION['user_license_no'] = $licenseNo;
            $_SESSION['user_profile_image'] = $profileImagePath;
            $success = 'Profile updated successfully.';
            $user = ['first_name' => $firstName, 'last_name' => $lastName, 'email' => $email, 'phone' => $phone, 'address' => $address, 'license_no' => $licenseNo, 'profile_image' => $profileImagePath];
        }
    }
    if ($error) {
        $user = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'license_no' => $licenseNo,
            'profile_image' => $profileImagePath,
        ];
    }
}

$pageTitle = 'Edit Profile';
include __DIR__ . '/partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-3 text-center">Edit Profile</h3>
                        <p class="text-muted small text-center mb-4">
                            Update your account and booking details.
                        </p>
                        <?php if (isset($_GET['welcome']) && $_GET['welcome'] === '1'): ?>
                            <div class="alert alert-success">Registration successful. Complete your profile details below.</div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>
                        <form method="post" action="edit-profile.php" enctype="multipart/form-data" novalidate>
                            <h6 class="text-muted text-uppercase small mb-2">Profile photo</h6>
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <?php if (!empty($user['profile_image'])): ?>
                                        <img
                                            src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                                            alt="Profile Photo"
                                            width="72"
                                            height="72"
                                            class="rounded-circle border"
                                            style="object-fit: cover;"
                                        />
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center" style="width:72px;height:72px;">
                                            <i class="bi bi-person fs-3 text-secondary"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1">
                                    <label for="profile_image" class="form-label">Upload photo (JPG, PNG, WEBP, max 2MB)</label>
                                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" />
                                </div>
                            </div>
                            <h6 class="text-muted text-uppercase small mb-2">Personal details</h6>
                            <div class="row g-2 mb-3">
                                <div class="col-sm-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required minlength="2"
                                        value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" />
                                    <div class="invalid-feedback">At least 2 characters.</div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required minlength="2"
                                        value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" />
                                    <div class="invalid-feedback">At least 2 characters.</div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" />
                                <div class="invalid-feedback">Please enter a valid email.</div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Mobile number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}"
                                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="10-digit number" />
                                <small class="text-muted">Optional for session accounts.</small>
                                <div class="invalid-feedback">Enter a valid 10-digit number.</div>
                            </div>

                            <h6 class="text-muted text-uppercase small mb-2 mt-4">Booking details</h6>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Street, city, state, pincode"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                <small class="text-muted">Used for pickup/delivery. Optional.</small>
                            </div>
                            <div class="mb-4">
                                <label for="license_no" class="form-label">Driver's license number</label>
                                <input type="text" class="form-control" id="license_no" name="license_no"
                                    value="<?php echo htmlspecialchars($user['license_no'] ?? ''); ?>" placeholder="e.g. DL-0120110123456" />
                                <small class="text-muted">Optional. Required for rental at booking.</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <a href="user-dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/partials/footer.php'; ?>
