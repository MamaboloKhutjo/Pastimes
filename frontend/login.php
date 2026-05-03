<?php
session_start();

// Only auto-redirect on GET requests (not POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (isset($_SESSION['user_id'])) {
        header("Location: home.php");
        exit();
    }
    if (isset($_SESSION['admin_id'])) {
        header("Location: admin.php");
        exit();
    }
}

require_once __DIR__ . '/../backend/DBConn.php';

$error = '';
$sticky_email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'login') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'] ?? 'buyer';
    
    $sticky_email = htmlspecialchars($email);
    
    // Admin check first
    $stmt = $conn->prepare("SELECT * FROM tblAdmin WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    
    if ($adminResult->num_rows == 1) {
        $admin = $adminResult->fetch_assoc();
        if (md5($password) === $admin['password_hash']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid admin password.";
        }
    } else {
        // Normal user
        $stmt = $conn->prepare("SELECT * FROM tblUser WHERE email = ? AND role = ?");
        $stmt->bind_param("ss", $email, $role);
        $stmt->execute();
        $userResult = $stmt->get_result();
        
        if ($userResult->num_rows == 1) {
            $user = $userResult->fetch_assoc();
            if (md5($password) === $user['password_hash']) {
                if ($user['verified'] == 1) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Your account is pending admin verification.";
                }
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No account found with that email and role.";
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Sign In</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .forgot-link{color:var(--clr-primary);font-size:.82rem;font-weight:500;cursor:pointer}
    .password-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
  </style>
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-logo text-center">Pastimes</div>
      <div class="auth-tagline">Curating Histories, One Piece at a Time</div>
 
      <!-- Role toggle (only relevant for non-admin users) -->
      <div class="auth-role-toggle" id="roleToggle">
        <div class="auth-role-btn active" data-role="buyer" onclick="setRole('buyer',this)">I'm a Buyer</div>
        <div class="auth-role-btn" data-role="seller" onclick="setRole('seller',this)">I'm a Seller</div>
      </div>
 
      <h1 class="auth-heading">Welcome back</h1>
 
      <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
 
      <button class="auth-social-btn" type="button">
        <i class="fab fa-google" style="color:#DB4437"></i> Continue with Google
      </button>
      <button class="auth-social-btn" type="button">
        <i class="fab fa-apple"></i> Continue with Apple
      </button>
 
      <div class="divider">or email</div>
 
      <!-- Unified login form -->
      <form id="loginForm" action="" method="POST">
        <input type="hidden" name="action" value="login">
        <input type="hidden" name="role" id="roleInput" value="buyer">
 
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input class="form-control" type="email" name="email" placeholder="you@example.co.za" value="<?= $sticky_email ?>" required>
        </div>
 
        <div class="form-group">
          <div class="password-row">
            <label class="form-label" style="margin:0">Password</label>
            <a class="forgot-link" href="forgot-password.html">Forgot?</a>
          </div>
          <input class="form-control" type="password" name="password" placeholder="••••••••" required>
        </div>
 
        <button class="btn btn-primary btn-block btn-lg" type="submit">Sign In</button>
      </form>
 
      <p class="auth-footer mt-16">Don't have an account? <a href="./register.php">Create one</a></p>
      <p class="auth-tagline-bottom">Wear it again. Sell it again.</p>
    </div>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>