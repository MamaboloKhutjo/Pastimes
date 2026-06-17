<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once __DIR__ . '/../backend/DBConn.php';

// Get admin info
$admin_id = $_SESSION['admin_id'];
$admin_query = $conn->query("SELECT full_name, email FROM tbladmin WHERE admin_id = $admin_id");
$admin = $admin_query->fetch_assoc();

// Approve user
if (isset($_GET['approve'])) {
    $uid = (int)$_GET['approve'];
    $conn->query("UPDATE tbluser SET verified = 1 WHERE user_id = $uid");
    header("Location: admin-registrations.php");
    exit();
}

// Reject (delete)
if (isset($_GET['reject'])) {
    $uid = (int)$_GET['reject'];
    $conn->query("DELETE FROM tbluser WHERE user_id = $uid");
    header("Location: admin-registrations.php");
    exit();
}

// Fetch pending users (verified = 0)
$pending = $conn->query("SELECT * FROM tbluser WHERE verified = 0 ORDER BY created_at DESC");
$pending_count = $pending->num_rows;

// Count by role
$seller_pending = $conn->query("SELECT COUNT(*) as count FROM tbluser WHERE verified = 0 AND role = 'seller'")->fetch_assoc()['count'];
$buyer_pending = $conn->query("SELECT COUNT(*) as count FROM tbluser WHERE verified = 0 AND role = 'buyer'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Admin Registrations</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body{background:#F5F0EC}
    .broadcast-btn{background:var(--clr-primary);color:#fff;border-radius:var(--radius-pill);padding:9px 20px;font-weight:600;font-size:.88rem;cursor:pointer}
  </style>
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="layout-admin">
 
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-logo">
        <div class="logo-mark">Pastimes</div>
        <div class="logo-sub">Admin Panel</div>
      </div>
 
      <nav class="sidebar-nav">
        <a class="nav-item" href="admin.php">
          <span class="nav-icon"><i class="fas fa-th-large"></i></span> Overview
        </a>
        <a class="nav-item active" href="admin-registrations.php">
          <span class="nav-icon"><i class="fas fa-user-plus"></i></span> Registrations
          <span class="nav-badge"><?= $pending_count ?></span>
        </a>
        <a class="nav-item" href="admin-sellers.php">
          <span class="nav-icon"><i class="fas fa-store"></i></span> Sellers
        </a>
        <a class="nav-item" href="admin-buyers.php">
          <span class="nav-icon"><i class="fas fa-users"></i></span> Buyers
        </a>
        <a class="nav-item" href="admin-listings.php">
          <span class="nav-icon"><i class="fas fa-tag"></i></span> Listings
        </a>
        <a class="nav-item" href="admin-flagged.php">
          <span class="nav-icon"><i class="fas fa-flag"></i></span> Flagged
          <span class="nav-badge" style="background:#E53E3E">3</span>
        </a>
        <a class="nav-item" href="admin-transactions.php">
          <span class="nav-icon"><i class="fas fa-exchange-alt"></i></span> Transactions
        </a>
        <a class="nav-item" href="admin-liaisons.php">
          <span class="nav-icon"><i class="fas fa-headset"></i></span> Liaisons
        </a>
      </nav>
 
      <div class="sidebar-footer">
        <div class="sidebar-user">
          <img src="./assets/images/profile_man1_50.jpg" alt="Admin">
          <div>
            <div class="sidebar-user-name">Admin · <?= htmlspecialchars($admin['full_name'] ?? 'Admin') ?></div>
            <div class="sidebar-user-email"><?= htmlspecialchars($admin['email'] ?? 'admin@pastimes.co.za') ?></div>
          </div>
        </div>
        <div class="sidebar-back" onclick="location.href='../backend/logout.php'">
          <i class="fas fa-arrow-left"></i> Login
        </div>
      </div>
    </aside>
 
    <!-- Main content -->
    <main class="main-content">
      <div class="topbar">
        <button class="icon-btn" style="display:none" id="hamburger"><i class="fas fa-bars"></i></button>
        <div class="topbar-title">Pending Registrations</div>
        <div class="topbar-actions">
          <button class="notif-btn icon-btn" style="position:relative">
            <i class="fas fa-bell"></i>
            <span class="notif-count">5</span>
          </button>
          <button class="broadcast-btn" onclick="openBroadcast()">Broadcast</button>
        </div>
      </div>
 
      <div class="page-body">
 
        <!-- Stats -->
        <div class="stats-grid">
          <div class="stat-card pink">
            <div class="stat-label">Total Pending</div>
            <div class="stat-value"><?= $pending_count ?></div>
            <div class="stat-delta">Needs Review</div>
          </div>
          <div class="stat-card blue">
            <div class="stat-label">Pending Sellers</div>
            <div class="stat-value" style="color:#2851A3"><?= $seller_pending ?></div>
          </div>
          <div class="stat-card green">
            <div class="stat-label">Pending Buyers</div>
            <div class="stat-value" style="color:#065F46"><?= $buyer_pending ?></div>
          </div>
        </div>
 
        <!-- Pending Registrations -->
        <div class="card">
          <div class="section-header">
            <div class="section-title">Review Registrations</div>
            <span class="badge badge-new"><?= $pending_count ?> new</span>
          </div>
 
          <div class="reg-grid">
            <?php if ($pending_count == 0): ?>
              <div style="padding: 40px; text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: #065F46; margin-bottom: 16px;"></i>
                <p class="text-muted" style="font-size: 16px;">All pending registrations have been reviewed!</p>
              </div>
            <?php else: ?>
              <?php while ($reg = $pending->fetch_assoc()): ?>
                <div class="reg-card">
                  <div class="d-flex align-center gap-12 mb-8">
                    <img class="reg-card-avatar" src="./assets/images/profile_man1.jpg" alt="<?= htmlspecialchars($reg['first_name']) ?>">
                    <div>
                      <div class="reg-card-name"><?= htmlspecialchars($reg['first_name'] . ' ' . $reg['last_name']) ?></div>
                      <div class="reg-card-email"><?= htmlspecialchars($reg['email']) ?></div>
                    </div>
                  </div>
                  <div class="reg-card-meta">
                    <span class="badge <?= $reg['role'] == 'seller' ? 'badge-seller' : 'badge-buyer' ?>"><?= ucfirst($reg['role']) ?></span>
                    <span><?= htmlspecialchars($reg['city'] ?: 'Location not set') ?></span>
                  </div>
                  <div class="reg-card-bio">"<?= htmlspecialchars(substr($reg['bio'] ?? 'No bio provided', 0, 100)) ?>"</div>
                  <div class="reg-card-actions">
                    <a href="admin-registrations.php?approve=<?= $reg['user_id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this user?')">Approve</a>
                    <a href="admin-registrations.php?reject=<?= $reg['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject and delete this user?')">Reject</a>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </div>
 
      </div>
    </main>
  </div>
 
  <!-- Broadcast modal -->
  <div id="broadcastModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center">
    <div class="card" style="width:460px;max-width:90vw">
      <h3 class="section-title mb-16">Broadcast Message</h3>
      <form action="../backend/broadcast_handler.php" method="POST">
        <input type="hidden" name="action" value="broadcast">
        <div class="form-group">
          <label class="form-label">Audience</label>
          <select class="form-control" name="audience">
            <option value="all">All Users</option>
            <option value="sellers">Sellers Only</option>
            <option value="buyers">Buyers Only</option>
            <option value="pending">Pending Registrations</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Message</label>
          <textarea class="form-control" name="message" rows="4" placeholder="Type your broadcast message..."></textarea>
        </div>
        <div class="d-flex gap-8 justify-between">
          <button type="button" class="btn btn-secondary" onclick="closeBroadcast()">Cancel</button>
          <button type="submit" class="btn btn-primary">Send Broadcast</button>
        </div>
      </form>
    </div>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>
