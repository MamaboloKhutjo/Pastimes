<?php
session_start();

// Correct path to DBConn.php (one level up then into backend)
require_once __DIR__ . '/../backend/DBConn.php';

$error = '';
$sticky = []; // to hold submitted values for sticky form

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'register') {
    // Sanitize and store inputs for sticky form
    $sticky['first_name'] = htmlspecialchars($_POST['first_name'] ?? '');
    $sticky['last_name'] = htmlspecialchars($_POST['last_name'] ?? '');
    $sticky['email'] = htmlspecialchars($_POST['email'] ?? '');
    $sticky['username'] = htmlspecialchars($_POST['username'] ?? '');
    $sticky['city'] = htmlspecialchars($_POST['city'] ?? '');
    $sticky['bio'] = htmlspecialchars($_POST['bio'] ?? '');
    $sticky['role'] = $_POST['role'] ?? 'buyer';
    
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';
    $role = $_POST['role'] ?? 'buyer';
    $city = $_POST['city'] ?? '';
    $bio = $_POST['bio'] ?? '';
    
    if (empty($first) || empty($last) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check if email exists
        $check = $conn->prepare("SELECT user_id FROM tblUser WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = "Email already registered. Please use a different email or login.";
        } else {
            $hash = md5($password); // MD5 as per specification
            $stmt = $conn->prepare("INSERT INTO tblUser (first_name, last_name, email, username, password_hash, role, city, bio, verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("ssssssss", $first, $last, $email, $username, $hash, $role, $city, $bio);
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful! Wait for admin approval.'); window.location='login.php';</script>";
                exit();
            } else {
                $error = "Registration failed: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Create Account</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="auth-page">
    <div class="auth-card">
      <div class="auth-logo text-center">Pastimes</div>
      <div class="auth-tagline">Curating Histories, One Piece at a Time</div>
 
      <div class="auth-role-toggle" id="roleToggle">
        <div class="auth-role-btn <?= ($sticky['role'] ?? 'buyer') == 'buyer' ? 'active' : '' ?>" data-role="buyer" onclick="setRole('buyer',this)">I'm a Buyer</div>
        <div class="auth-role-btn <?= ($sticky['role'] ?? 'buyer') == 'seller' ? 'active' : '' ?>" data-role="seller" onclick="setRole('seller',this)">I'm a Seller</div>
      </div>
 
      <h1 class="auth-heading">Create account</h1>
 
      <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
 
      <!-- Form submits to itself -->
      <form id="registerForm" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="register">
        <input type="hidden" name="role" id="roleInput" value="<?= htmlspecialchars($sticky['role'] ?? 'buyer') ?>">
 
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">First Name</label>
            <input class="form-control" type="text" name="first_name" placeholder="Elena" value="<?= htmlspecialchars($sticky['first_name'] ?? '') ?>" required>
          </div>
          <div class="form-group">
            <label class="form-label">Last Name</label>
            <input class="form-control" type="text" name="last_name" placeholder="Vance" value="<?= htmlspecialchars($sticky['last_name'] ?? '') ?>" required>
          </div>
        </div>
 
        <div class="form-group">
          <label class="form-label">Email Address</label>
          <input class="form-control" type="email" name="email" placeholder="you@example.co.za" value="<?= htmlspecialchars($sticky['email'] ?? '') ?>" required>
        </div>
 
        <div class="form-group">
          <label class="form-label">Username / Handle</label>
          <input class="form-control" type="text" name="username" placeholder="@the_archivist" value="<?= htmlspecialchars($sticky['username'] ?? '') ?>">
        </div>
 
        <div class="form-group">
          <label class="form-label">City</label>
          <select class="form-control" name="city">
            <option value="">Select city...</option>
            <option <?= ($sticky['city'] ?? '') == 'Cape Town, WC' ? 'selected' : '' ?>>Cape Town, WC</option>
            <option <?= ($sticky['city'] ?? '') == 'Johannesburg, GP' ? 'selected' : '' ?>>Johannesburg, GP</option>
            <option <?= ($sticky['city'] ?? '') == 'Pretoria, GP' ? 'selected' : '' ?>>Pretoria, GP</option>
            <option <?= ($sticky['city'] ?? '') == 'Durban, KZN' ? 'selected' : '' ?>>Durban, KZN</option>
            <option <?= ($sticky['city'] ?? '') == 'Port Elizabeth, EC' ? 'selected' : '' ?>>Port Elizabeth, EC</option>
            <option <?= ($sticky['city'] ?? '') == 'Other' ? 'selected' : '' ?>>Other</option>
          </select>
        </div>
 
        <!-- Seller-only fields -->
        <div id="sellerFields" style="<?= ($sticky['role'] ?? 'buyer') == 'seller' ? 'display:block' : 'display:none' ?>">
          <div class="form-group">
            <label class="form-label">Bio / Description</label>
            <textarea class="form-control" name="bio" rows="3" placeholder="Tell buyers about your collection..."><?= htmlspecialchars($sticky['bio'] ?? '') ?></textarea>
          </div>
        </div>
 
        <div class="form-group">
          <label class="form-label">Password</label>
          <input class="form-control" type="password" name="password" placeholder="••••••••" required minlength="8">
        </div>
 
        <div class="form-group">
          <label class="form-label">Confirm Password</label>
          <input class="form-control" type="password" name="password_confirm" placeholder="••••••••" required>
        </div>
 
        <button class="btn btn-primary btn-block btn-lg" type="submit">Create Account</button>
      </form>
 
      <p class="auth-footer mt-16">Already have an account? <a href="login.php">Sign in</a></p>
      <p class="auth-tagline-bottom">Wear it again. Sell it again.</p>
    </div>
  </div>
 
  <script src="../frontend/js/script.js"></script>

</body>
</html>