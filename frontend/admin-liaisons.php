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

// Sample liaison team data - adjust based on your actual schema
$liaisons_query = $conn->query("
    SELECT 
        *
    FROM tbladmin
    ORDER BY admin_id DESC
");
$liaisons_count = $liaisons_query->num_rows;

// Get support metrics
$open_tickets = 5; // Placeholder - adjust based on your support ticket system
$resolved_tickets = 23; // Placeholder
$avg_resolution = '2.5h'; // Placeholder
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Admin Liaisons</title>
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
        <a class="nav-item" href="admin-transactions.php">
          <span class="nav-icon"><i class="fas fa-exchange-alt"></i></span> Transactions
        </a>
        <a class="nav-item active" href="admin-liaisons.php">
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
        <div class="topbar-title">Customer Liaisons</div>
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
            <div class="stat-label">Active Liaisons</div>
            <div class="stat-value"><?= $liaisons_count ?></div>
          </div>
          <div class="stat-card green">
            <div class="stat-label">Open Tickets</div>
            <div class="stat-value" style="color:#065F46"><?= $open_tickets ?></div>
          </div>
          <div class="stat-card blue">
            <div class="stat-label">Resolved This Month</div>
            <div class="stat-value" style="color:#2851A3"><?= $resolved_tickets ?></div>
          </div>
          <div class="stat-card yellow">
            <div class="stat-label">Avg Resolution Time</div>
            <div class="stat-value" style="color:#92400E"><?= $avg_resolution ?></div>
          </div>
        </div>
 
        <!-- Liaisons Team -->
        <div class="card mb-24">
          <div class="section-header">
            <div class="section-title">Liaison Team Members</div>
            <button class="btn btn-sm btn-primary" onclick="alert('Add new liaison feature - coming soon')">+ Add Liaison</button>
          </div>
 
          <div class="team-grid">
            <?php if ($liaisons_count == 0): ?>
              <p class="text-muted">No liaisons added yet.</p>
            <?php else: ?>
              <?php while ($liaison = $liaisons_query->fetch_assoc()): ?>
                <div class="team-card">
                  <div class="team-card-header">
                    <img class="team-card-avatar" src="./assets/images/profile_man1.jpg" alt="<?= htmlspecialchars($liaison['full_name']) ?>">
                    <div class="team-card-status online"></div>
                  </div>
                  <div class="team-card-name"><?= htmlspecialchars($liaison['full_name']) ?></div>
                  <div class="team-card-email"><?= htmlspecialchars($liaison['email']) ?></div>
                  <div class="team-card-meta">
                    <span class="badge badge-admin">Admin</span>
                  </div>
                  <div class="team-card-actions">
                    <button class="btn btn-sm btn-secondary" title="Message">
                      <i class="fas fa-envelope"></i> Message
                    </button>
                  </div>
                </div>
              <?php endwhile; ?>
            <?php endif; ?>
          </div>
        </div>
 
        <!-- Support Tickets -->
        <div class="card">
          <div class="section-header">
            <div class="section-title">Recent Support Tickets</div>
          </div>
 
          <table class="data-table">
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>From</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#1001</td>
                <td>John Doe</td>
                <td>Payment not received</td>
                <td><span class="badge badge-warning">Open</span></td>
                <td>Admin</td>
                <td>2 hours ago</td>
                <td>
                  <button class="icon-btn" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>#1002</td>
                <td>Jane Smith</td>
                <td>Delivery inquiry</td>
                <td><span class="badge badge-success">Resolved</span></td>
                <td>Admin</td>
                <td>1 day ago</td>
                <td>
                  <button class="icon-btn" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>#1003</td>
                <td>Bob Wilson</td>
                <td>Refund request</td>
                <td><span class="badge badge-warning">In Progress</span></td>
                <td>Admin</td>
                <td>3 hours ago</td>
                <td>
                  <button class="icon-btn" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                </td>
              </tr>
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
