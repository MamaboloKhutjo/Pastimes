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
    
    $sticky_email = htmlspecialchars($email);
    
    // Admin check first
    $stmt = $conn->prepare("SELECT * FROM tbladmin WHERE email = ?");
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
        // Normal user (no role needed for login - user's role is stored in their account)
        $stmt = $conn->prepare("SELECT * FROM tbluser WHERE email = ?");
        $stmt->bind_param("s", $email);
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
            $error = "No account found with that email.";
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
    .auth-social-btn{display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:12px 16px;margin-bottom:10px;border:1px solid #e0e0e0;border-radius:8px;background:#fff;font-size:14px;font-weight:500;cursor:pointer;transition:all 0.2s;color:#333}
    .auth-social-btn:hover{background:#f5f5f5;border-color:#999}
    .auth-social-btn svg{flex-shrink:0}
    .divider{text-align:center;margin:18px 0;color:#999;font-size:13px;position:relative}
    .divider::before{content:'';position:absolute;top:50%;left:0;right:0;height:1px;background:#ddd}
  </style>
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-logo text-center">Pastimes</div>
      <div class="auth-tagline">Curating Histories, One Piece at a Time</div>
 
      <h1 class="auth-heading">Welcome back</h1>
 
      <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
 
      <button class="auth-social-btn" type="button" onclick="alert('Google Sign-In coming soon')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Continue with Google
      </button>
      <button class="auth-social-btn" type="button" onclick="alert('Apple Sign-In coming soon')">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
          <path d="M17.05 13.5c-.91 0-1.63.2-2.25.56.22-1.41.74-2.61 1.53-3.61.59-.71 1.28-1.41 1.28-2.3 0-.91-.36-1.61-1.09-2.16-.55-.41-1.28-.51-1.78-.51-1.13 0-2.29.46-3.16 1.27-.47.43-.86.96-1.11 1.58-.29.75-.5 1.52-.64 2.31-.2 1.19-.38 2.38-.87 3.38-.3.62-.89 1.11-1.58 1.11-.79 0-1.43-.64-1.43-1.43 0-1.12.91-2.03 2.03-2.03.55 0 1.08.22 1.5.64.2.2.38.42.53.65.38.59.59 1.28.59 2.03 0 1.64-.59 3.19-1.67 4.34-.55.57-1.28 1.07-2.03 1.43-.75.36-1.57.56-2.39.56-1.99 0-3.85-.81-5.17-2.26-.76-.8-1.36-1.79-1.75-2.86-.39-1.07-.59-2.2-.59-3.36 0-.99.13-1.97.4-2.91.27-.94.68-1.83 1.2-2.64.52-.81 1.14-1.54 1.86-2.18.72-.64 1.54-1.18 2.42-1.59 1.19-.55 2.53-.84 3.86-.84 1.79 0 3.47.4 5.0 1.15 1.19.56 2.2 1.32 2.98 2.27z"/>
        </svg>
        Continue with Apple
      </button>
 
      <div class="divider">or email</div>
 
      <!-- Login form -->
      <form id="loginForm" action="" method="POST">
        <input type="hidden" name="action" value="login">
 
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
 
      <p class="auth-footer mt-16">Don't have an account? <a href="./register.php">Sign up</a></p>
      <p class="auth-tagline-bottom">Wear it again. Sell it again.</p>
    </div>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>