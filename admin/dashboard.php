<?php
require __DIR__ . '/auth.php';
// auth.php handles session timeout and security checks

$admin_username = htmlspecialchars($_SESSION['admin_username'] ?? 'Admin');
$admin_id = $_SESSION['admin_id'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f6f9;
    }
    .navbar-custom {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .sidebar {
      background: #fff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      min-height: 100vh;
      padding-top: 2rem;
    }
    .sidebar .nav-link {
      color: #555;
      padding: 0.75rem 1.5rem;
      border-left: 3px solid transparent;
      transition: all 0.3s;
    }
    .sidebar .nav-link:hover {
      background-color: #f5f6f9;
      border-left-color: #667eea;
      color: #667eea;
    }
    .sidebar .nav-link.active {
      background-color: #f0f2ff;
      border-left-color: #667eea;
      color: #667eea;
      font-weight: 600;
    }
    .main-content {
      padding: 2rem;
    }
    .welcome-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }
    .welcome-card h5 {
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    .welcome-card p {
      margin: 0;
      opacity: 0.9;
    }
    .info-card {
      background: white;
      border: none;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .info-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    .badge-custom {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 0.5rem 1rem;
      border-radius: 20px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-dark navbar-custom sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Car Rental Admin
      </a>
      <div class="d-flex align-items-center gap-3">
        <div class="dropdown">
          <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle"></i> <?php echo $admin_username; ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
              <a class="dropdown-item" href="#" onclick="showSessionInfo()">
                <i class="bi bi-info-circle"></i> Session Info
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-danger" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                <i class="bi bi-box-arrow-right"></i> Logout
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-2 sidebar">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard.php">
              <i class="bi bi-speedometer2"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Features coming in CIE 2">
              <i class="bi bi-people"></i> Users
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Features coming in CIE 2">
              <i class="bi bi-car-front"></i> Vehicles
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Features coming in CIE 2">
              <i class="bi bi-calendar-check"></i> Bookings
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Features coming in CIE 2">
              <i class="bi bi-credit-card"></i> Payments
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="tooltip" title="Features coming in CIE 2">
              <i class="bi bi-graph-up"></i> Reports
            </a>
          </li>
        </ul>
      </nav>

      <!-- Main Content -->
      <main class="col-md-10 main-content">
        <!-- Welcome Section -->
        <div class="welcome-card card mb-4">
          <div class="card-body">
            <h5>Welcome back, <strong><?php echo $admin_username; ?></strong>!</h5>
            <p>You are logged into the Car Rental Admin Panel. Use the menu to manage your system.</p>
          </div>
        </div>

        <!-- Alert for Session Expiration -->
        <?php if (isset($_GET['expired'])): ?>
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> Your session has expired. Please login again.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- Dashboard Cards -->
        <div class="row">
          <div class="col-md-6 col-lg-3 mb-4">
            <div class="info-card card">
              <div class="card-body text-center">
                <i class="bi bi-people-fill" style="font-size: 2rem; color: #667eea;"></i>
                <h6 class="card-title mt-3">Users</h6>
                <p class="text-muted small">Manage users (CIE 2)</p>
                <span class="badge badge-custom">Coming Soon</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 mb-4">
            <div class="info-card card">
              <div class="card-body text-center">
                <i class="bi bi-car-front-fill" style="font-size: 2rem; color: #667eea;"></i>
                <h6 class="card-title mt-3">Vehicles</h6>
                <p class="text-muted small">Manage vehicles (CIE 2)</p>
                <span class="badge badge-custom">Coming Soon</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 mb-4">
            <div class="info-card card">
              <div class="card-body text-center">
                <i class="bi bi-calendar-check-fill" style="font-size: 2rem; color: #667eea;"></i>
                <h6 class="card-title mt-3">Bookings</h6>
                <p class="text-muted small">Manage bookings (CIE 2)</p>
                <span class="badge badge-custom">Coming Soon</span>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-lg-3 mb-4">
            <div class="info-card card">
              <div class="card-body text-center">
                <i class="bi bi-credit-card-fill" style="font-size: 2rem; color: #667eea;"></i>
                <h6 class="card-title mt-3">Payments</h6>
                <p class="text-muted small">View payments (CIE 2)</p>
                <span class="badge badge-custom">Coming Soon</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Status Section -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="info-card card">
              <div class="card-body">
                <h5 class="card-title">System Status</h5>
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i> 
                    <strong>Database Connection:</strong> <span class="badge bg-success">Active</span>
                  </li>
                  <li class="mb-2">
                    <i class="bi bi-check-circle-fill text-success"></i> 
                    <strong>Session:</strong> <span class="badge bg-success">Active</span>
                  </li>
                  <li class="mb-2">
                    <i class="bi bi-info-circle-fill text-info"></i> 
                    <strong>Admin ID:</strong> <code><?php echo htmlspecialchars($admin_id); ?></code>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal for Session Info -->
  <div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sessionModalLabel">Session Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Username:</strong> <?php echo $admin_username; ?></p>
          <p><strong>Admin ID:</strong> <?php echo htmlspecialchars($admin_id); ?></p>
          <p><strong>Session Status:</strong> Active</p>
          <p><strong>Session Timeout:</strong> 30 minutes of inactivity</p>
          <p class="text-muted small">Your session will automatically expire if inactive for more than 30 minutes.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Show session info modal
    function showSessionInfo() {
      const modal = new bootstrap.Modal(document.getElementById('sessionModal'));
      modal.show();
    }

    // Auto-logout warning (25 minutes)
    setTimeout(() => {
      const remaining = 5; // minutes
      if (confirm(`Your session will expire in ${remaining} minutes due to inactivity. Do you want to stay logged in?`)) {
        // User clicked OK - session continues
      } else {
        // User clicked Cancel - logout
        window.location.href = 'logout.php';
      }
    }, 25 * 60 * 1000);
  </script>
</body>
</html>
