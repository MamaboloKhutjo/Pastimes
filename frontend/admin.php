<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

// Correct path to DBConn.php
require_once __DIR__ . '/../backend/DBConn.php';

// Get admin info
$admin_id = $_SESSION['admin_id'];
$admin_query = $conn->query("SELECT full_name, email FROM tblAdmin WHERE admin_id = $admin_id");
$admin = $admin_query->fetch_assoc();

// Approve user
if (isset($_GET['approve'])) {
    $uid = (int)$_GET['approve'];
    $conn->query("UPDATE tblUser SET verified = 1 WHERE user_id = $uid");
    header("Location: admin.php");
    exit();
}

// Reject (delete)
if (isset($_GET['reject'])) {
    $uid = (int)$_GET['reject'];
    $conn->query("DELETE FROM tblUser WHERE user_id = $uid");
    header("Location: admin.php");
    exit();
}

// Fetch dashboard stats
$stats = [];
// Total sellers
$result = $conn->query("SELECT COUNT(*) as count FROM tblUser WHERE role = 'seller' AND verified = 1");
$stats['total_sellers'] = $result->fetch_assoc()['count'];
// Total buyers
$result = $conn->query("SELECT COUNT(*) as count FROM tblUser WHERE role = 'buyer' AND verified = 1");
$stats['total_buyers'] = $result->fetch_assoc()['count'];
// Pending registrations (verified = 0)
$result = $conn->query("SELECT COUNT(*) as count FROM tblUser WHERE verified = 0");
$stats['pending_reviews'] = $result->fetch_assoc()['count'];
// This week new sellers (example)
$result = $conn->query("SELECT COUNT(*) as count FROM tblUser WHERE role = 'seller' AND verified = 1 AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$stats['sellers_this_week'] = $result->fetch_assoc()['count'];
// This week new buyers
$result = $conn->query("SELECT COUNT(*) as count FROM tblUser WHERE role = 'buyer' AND verified = 1 AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$stats['buyers_this_week'] = $result->fetch_assoc()['count'];

// GMV this month (sum of total_amount from orders with status paid)
$result = $conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM tblAorder WHERE status = 'paid' AND MONTH(order_date) = MONTH(NOW()) AND YEAR(order_date) = YEAR(NOW())");
$gmv = $result->fetch_assoc()['total'];
$stats['gmv_this_month'] = 'R ' . number_format($gmv / 1000, 0) . 'k';
// Dummy last month comparison (simplified)
$stats['gmv_vs_last_month'] = 23;

// Fetch pending users (verified = 0)
$pending = $conn->query("SELECT * FROM tblUser WHERE verified = 0 ORDER BY created_at DESC");
$pending_count = $pending->num_rows;

// Fetch recent transactions (from tblAorder and tblUser)
$transactions = $conn->query("
    SELECT o.order_id, o.total_amount, o.status, o.order_date,
           CONCAT(u.first_name, ' ', u.last_name) as user_name, u.role,
           'Item' as item_name
    FROM tblAorder o
    JOIN tblUser u ON o.buyer_id = u.user_id
    ORDER BY o.order_date DESC
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Admin Dashboard</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Admin-only overrides */
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
        <a class="nav-item active" href="admin.php">
          <span class="nav-icon"><i class="fas fa-th-large"></i></span> Overview
        </a>
        <a class="nav-item" href="admin.php?tab=pending">
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
        <div class="topbar-title">Dashboard Overview</div>
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
            <div class="stat-label">Total Sellers</div>
            <div class="stat-value"><?= number_format($stats['total_sellers']) ?></div>
            <div class="stat-delta">↑ <?= $stats['sellers_this_week'] ?> this week</div>
          </div>
          <div class="stat-card blue">
            <div class="stat-label">Total Buyers</div>
            <div class="stat-value" style="color:#2851A3"><?= number_format($stats['total_buyers']) ?></div>
            <div class="stat-delta">↑ <?= $stats['buyers_this_week'] ?> this week</div>
          </div>
          <div class="stat-card yellow">
            <div class="stat-label">GMV This Month</div>
            <div class="stat-value" style="color:#92400E"><?= $stats['gmv_this_month'] ?></div>
            <div class="stat-delta">↑ <?= $stats['gmv_vs_last_month'] ?>% vs last month</div>
          </div>
          <div class="stat-card green">
            <div class="stat-label">Pending Reviews</div>
            <div class="stat-value" style="color:#065F46"><?= $stats['pending_reviews'] ?></div>
            <div class="stat-delta">Needs attention</div>
          </div>
        </div>
 
        <!-- Pending Registrations -->
        <div class="card mb-24">
          <div class="section-header">
            <div class="section-title">Pending Registrations</div>
            <span class="badge badge-new"><?= $pending_count ?> new</span>
          </div>
 
          <div class="reg-grid">
            <?php if ($pending_count == 0): ?>
              <p class="text-muted">No pending registrations.</p>
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
                    <a href="admin.php?approve=<?= $reg['user_id'] ?>" class="btn btn-success btn-sm" onclick="return confirm('Approve this user?')">Approve</a>
                    <a href="admin.php?reject=<?= $reg['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Reject and delete this user?')">Reject</a>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </div>
 
        <!-- Recent Transactions -->
        <div class="card">
          <div class="section-header">
            <div class="section-title">Recent Transactions</div>
            <a href="admin-transactions.php" class="text-primary fs-sm fw-600">View all</a>
          </div>
 
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Item</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($transactions->num_rows == 0): ?>
                <tr><td colspan="5" class="text-center">No transactions yet.</td></tr>
              <?php else: ?>
                <?php while ($t = $transactions->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($t['user_name']) ?></td>
                    <td><span class="badge <?= $t['role'] == 'seller' ? 'badge-seller' : 'badge-buyer' ?>"><?= ucfirst($t['role']) ?></span></td>
                    <td><?= htmlspecialchars($t['item_name']) ?></td>
                    <td class="amount-col">R <?= number_format($t['total_amount'], 0, '.', ' ') ?></td>
                    <td><span class="badge badge-<?= $t['status'] == 'paid' ? 'paid' : 'pending' ?>"><?= ucfirst($t['status']) ?></span></td>
                  </tr>
                <?php endwhile; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
 
      </div>
    </main>
  </div>
 
  <!-- Broadcast modal -->
  <div id="broadcastModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:100;align-items:center;justify-content:center">
    <div class="card" style="width:460px;max-width:90vw">
      <h3 class="section-title mb-16">Broadcast Message</h3>
      <!-- Form submits to backend broadcast handler -->
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