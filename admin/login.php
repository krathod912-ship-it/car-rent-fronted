<?php
// Admin login (no public links anywhere)
$pdo = require __DIR__ . '/db.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = false;

// If DB connection failed, $pdo will be null (db.php logs the real error).
$db_unavailable = ($pdo === null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  // Server-side validation
  if (empty($username)) {
    $errors[] = 'Username is required.';
  } elseif (strlen($username) < 3) {
    $errors[] = 'Username must be at least 3 characters.';
  }

  if (empty($password)) {
    $errors[] = 'Password is required.';
  } elseif (strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters.';
  }

  if ($db_unavailable) {
    $errors[] = 'Database currently unavailable. Please try again later.';
  } elseif (empty($errors)) {
    // Only query if validation passed
    try {
      $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1');
      $stmt->execute([':username' => $username]);
      $admin = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['login_time'] = time();
        header('Location: dashboard.php');
        exit;
      } else {
        $errors[] = 'Invalid username or password.';
      }
    } catch (PDOException $e) {
      error_log('Login query failed: ' . $e->getMessage());
      $errors[] = 'An error occurred. Please try again later.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login - Car Rental</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-card {
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      border: none;
      border-radius: 10px;
    }
    .login-card .card-body {
      padding: 3rem 2rem;
    }
    .card-title {
      font-weight: 700;
      color: #333;
      text-align: center;
      margin-bottom: 2rem;
    }
    .form-label {
      font-weight: 600;
      color: #555;
      margin-bottom: 0.5rem;
    }
    .form-control {
      border-radius: 5px;
      border: 1px solid #ddd;
      padding: 0.75rem;
      font-size: 0.95rem;
    }
    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 0.75rem;
      font-weight: 600;
      border-radius: 5px;
      transition: transform 0.2s;
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
    .error-icon {
      display: inline-block;
      margin-right: 0.5rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5 col-xl-4">
        <div class="card login-card">
          <div class="card-body">
            <h4 class="card-title">
              <i class="bi bi-shield-lock"></i> Admin Login
            </h4>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach ($errors as $error): ?>
                  <div class="mb-2">
                    <i class="bi bi-exclamation-circle error-icon"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                  </div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['logout']) && $_GET['logout'] === 'success'): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> You have been logged out successfully. See you next time!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <?php if (isset($_GET['expired'])): ?>
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> Your session has expired. Please login again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <form method="post" action="" id="loginForm" novalidate>
              <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="username"
                  name="username" 
                  required 
                  autofocus
                  minlength="3"
                  placeholder="Enter your username"
                  value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                >
                <small class="form-text text-muted">At least 3 characters required</small>
              </div>

              <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="password"
                  name="password" 
                  required
                  minlength="6"
                  placeholder="Enter your password"
                >
                <small class="form-text text-muted">At least 6 characters required</small>
              </div>

              <div class="d-grid gap-2">
                <button class="btn btn-login btn-primary text-white" type="submit">
                  <i class="bi bi-box-arrow-in-right"></i> Sign in
                </button>
              </div>
            </form>
          </div>
        </div>
        <p class="text-white text-center small mt-3">Car Rental Admin Panel</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Client-side form validation
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      const errors = [];

      if (!username) {
        errors.push('Username is required.');
      } else if (username.length < 3) {
        errors.push('Username must be at least 3 characters.');
      }

      if (!password) {
        errors.push('Password is required.');
      } else if (password.length < 6) {
        errors.push('Password must be at least 6 characters.');
      }

      if (errors.length > 0) {
        e.preventDefault();
        alert(errors.join('\n'));
      }
    });
  </script>
</body>
</html>
