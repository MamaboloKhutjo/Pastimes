<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}

require_once __DIR__ . '/../backend/DBConn.php';

// Get admin info
$admin_id = $_SESSION['admin_id'];
$admin_query = $conn->query("SELECT full_name, email FROM tblAdmin WHERE admin_id = $admin_id");
$admin = $admin_query->fetch_assoc();

// Fetch all transactions
$transactions = $conn->query("
    SELECT 
        o.order_id,
        CONCAT(u.first_name, ' ', u.last_name) as buyer_name,
        CONCAT(s.first_name, ' ', s.last_name) as seller_name,
        GROUP_CONCAT(c.title SEPARATOR ', ') as product_names,
        o.total_amount,
        o.status,
        o.order_date
    FROM tblaorder o
    JOIN tbluser u ON o.buyer_id = u.user_id
    LEFT JOIN tblorderitem oi ON o.order_id = oi.order_id
    LEFT JOIN tblclothes c ON oi.clothing_id = c.clothing_id
    LEFT JOIN tbluser s ON c.seller_id = s.user_id
    GROUP BY o.order_id
    ORDER BY o.order_date DESC
");
$total_transactions = $transactions->num_rows;

// Calculate stats
$total_revenue = $conn->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM tblaorder WHERE status = 'paid'")->fetch_assoc()['total'];
$pending_transactions = $conn->query("SELECT COUNT(*) as count FROM tblaorder WHERE status = 'pending'")->fetch_assoc()['count'];
$completed = $conn->query("SELECT COUNT(*) as count FROM tblaorder WHERE status = 'paid'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Admin Transactions</title>
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
        <a class="nav-item" href="admin-registrations.php">
          <span class="nav-icon"><i class="fas fa-user-plus"></i></span> Registrations
          <span class="nav-badge">0</span>
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
        <a class="nav-item active" href="admin-transactions.php">
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
        <div class="topbar-title">Transactions</div>
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
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">R <?= number_format($total_revenue / 1000, 0) ?>k</div>
          </div>
          <div class="stat-card green">
            <div class="stat-label">Completed</div>
            <div class="stat-value" style="color:#065F46"><?= $completed ?></div>
          </div>
          <div class="stat-card yellow">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color:#92400E"><?= $pending_transactions ?></div>
          </div>
          <div class="stat-card blue">
            <div class="stat-label">Total Transactions</div>
            <div class="stat-value" style="color:#2851A3"><?= $total_transactions ?></div>
          </div>
        </div>
 
        <!-- Transactions Table -->
        <div class="card">
          <div class="section-header">
            <div class="section-title">All Transactions</div>
          </div>
 
          <table class="data-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($total_transactions == 0): ?>
                <tr><td colspan="8" class="text-center">No transactions yet.</td></tr>
              <?php else: ?>
                <?php $transactions->data_seek(0); ?>
                <?php while ($t = $transactions->fetch_assoc()): ?>
                  <tr>
                    <td>#<?= $t['order_id'] ?></td>
                    <td><?= htmlspecialchars($t['buyer_name']) ?></td>
                    <td><?= htmlspecialchars($t['seller_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($t['product_names'] ?? 'N/A') ?></td>
                    <td class="amount-col">R <?= number_format($t['total_amount'], 0, '.', ' ') ?></td>
                    <td>
                      <span class="badge badge-<?= $t['status'] == 'paid' ? 'success' : 'warning' ?>">
                        <?= ucfirst($t['status']) ?>
                      </span>
                    </td>
                    <td><?= date('d M Y', strtotime($t['order_date'])) ?></td>
                    <td>
                      <button class="icon-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                      </button>
                    </td>
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
