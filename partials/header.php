<?php
// Common header with Bootstrap and main navbar
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
    <!-- Custom CSS (relative path so it works inside /car-rental folder) -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler:focus {
            box-shadow: none;
            outline: 1px solid rgba(255, 255, 255, 0.5);
        }
        .navbar {
            padding: 1.5rem 0;
            min-height: 100px;
        }
        .navbar-brand {
            font-size: 1.8rem;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <!-- Main Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-bold" href="index.php">
                <span class="text-primary">Car</span>Rent
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 flex-column mt-3">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Search &amp; Filter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">

