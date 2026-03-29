<?php
// Common header with Bootstrap and main navbar
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '';
$userProfileImage = $_SESSION['user_profile_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?>CarRent</title>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- Custom CSS (relative path so it works inside /car-rental folder) -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .navbar {
        padding: 1rem 0;
        background: linear-gradient(135deg, #1a1f2e 0%, #0d1b2a 100%) !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .navbar-brand {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .navbar-brand .text-primary {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .navbar-nav {
        gap: 0.5rem;
    }

    .nav-link {
        color: #0d6efd !important;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.75rem 1.25rem !important;
        border-radius: 6px;
    }

    /* Remove hover animation */
    .nav-link:hover {
        color: #0d6efd !important;
        background: none;
        transform: none;
    }

    /* Remove underline animation */
    .nav-link::after {
        display: none;
    }

    /* Login / Profile link style */
    .navbar-nav .nav-item .nav-link.nav-login {
        border: 1px solid #0d6efd;
    }

    /* Sign Up / Logout - primary style */
    .navbar-nav .nav-item .nav-link.nav-signup {
        background-color: #0d6efd;
        color: #ffffff !important;
        border: none;
    }

    .navbar-nav .nav-item .nav-link.nav-logout {
        color: #dc3545 !important;
        border: 1px solid #dc3545;
    }
    .navbar-nav .nav-item .nav-link.nav-logout:hover {
        background-color: #dc3545 !important;
        color: #ffffff !important;
    }

    @media (max-width: 768px) {
        .nav-link {
            padding: 0.6rem 0.9rem !important;
            font-size: 0.85rem;
        }

        .navbar-brand {
            font-size: 1.5rem;
        }
    }
</style>
</head>
<body>
    <!-- Main Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <span class="text-primary">Car</span>Rent
            </a>
            <ul class="navbar-nav flex-row ms-auto align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link" href="booking.php">Booking</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link" href="search.php">Cars</a>
                </li>
                <?php if ($userLoggedIn): ?>
                <li class="nav-item me-3">
                    <a class="nav-link nav-login d-flex align-items-center" href="edit-profile.php">
                        <?php if ($userProfileImage): ?>
                            <img
                                src="<?php echo htmlspecialchars($userProfileImage); ?>"
                                alt="Profile"
                                width="24"
                                height="24"
                                class="rounded-circle me-2"
                                style="object-fit: cover;"
                            />
                        <?php else: ?>
                            <i class="bi bi-person-circle me-1"></i>
                        <?php endif; ?>
                        Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-logout" href="user-logout.php" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item me-3">
                    <a class="nav-link nav-login" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-signup" href="register.php">Sign Up</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="flex-grow-1">

