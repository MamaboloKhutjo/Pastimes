<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

require_once __DIR__ . '/../backend/DBConn.php';

$user_id = (int)($_GET['id'] ?? 0);

if ($user_id === 0) {
    header("Location: admin.php");
    exit();
}

// Fetch user details
$stmt = $conn->prepare("
    SELECT u.*, 
           COUNT(DISTINCT c.clothing_id) as listings_count,
           COUNT(DISTINCT o.order_id) as orders_count,
           COALESCE(SUM(o.total_amount), 0) as total_spent
    FROM tbluser u
    LEFT JOIN tblclothes c ON u.user_id = c.seller_id
    LEFT JOIN tblaorder o ON u.user_id = o.buyer_id AND o.status = 'paid'
    WHERE u.user_id = ?
    GROUP BY u.user_id
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — View Profile #<?= $user_id ?></title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body{background:#F5F0EC}
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
        <a class="nav-item" href="admin.php"><i class="fas fa-th-large"></i> Overview</a>
        <a class="nav-item" href="admin-sellers.php"><i class="fas fa-store"></i> Sellers</a>
        <a class="nav-item" href="admin-buyers.php"><i class="fas fa-users"></i> Buyers</a>
      </nav>
      <div class="sidebar-footer">
        <div class="sidebar-back" onclick="location.href='../backend/logout.php'">
          <i class="fas fa-arrow-left"></i> Logout
        </div>
      </div>
    </aside>

    <main class="main-content">
      <div class="topbar">
        <a href="admin-sellers.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
        <div class="topbar-title">User Profile #<?= $user_id ?></div>
      </div>

      <div class="page-body">
        <div class="card">
          <div class="profile-header" style="padding:24px">
            <div style="display:flex; gap:20px; align-items:center">
              <img src="./assets/images/profile_man1.jpg" alt="<?= htmlspecialchars($user['first_name']) ?>" 
                   style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #fff">
              <div>
                <h2><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                <p><strong>@<?= htmlspecialchars($user['username'] ?? 'No username') ?></strong> • <?= htmlspecialchars($user['city'] ?? 'No location') ?></p>
                <span class="badge <?= $user['role'] == 'seller' ? 'badge-seller' : 'badge-buyer' ?>">
                  <?= strtoupper($user['role']) ?>
                </span>
                <span class="badge <?= $user['verified'] ? 'badge-approved' : 'badge-warning' ?>">
                  <?= $user['verified'] ? 'Verified' : 'Pending' ?>
                </span>
              </div>
            </div>

            <div style="margin-top:20px">
              <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($user['bio'] ?? 'No bio provided')) ?></p>
            </div>
          </div>
        </div>

        <div class="stats-grid" style="margin-top:24px">
          <div class="stat-card">
            <div class="stat-label">Listings</div>
            <div class="stat-value"><?= number_format($user['listings_count']) ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Orders</div>
            <div class="stat-value"><?= number_format($user['orders_count']) ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">R <?= number_format($user['total_spent']) ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Joined</div>
            <div class="stat-value"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="card" style="margin-top:24px">
          <div class="section-header">
            <div class="section-title">Admin Actions</div>
          </div>
          <div style="padding:20px; display:flex; gap:12px; flex-wrap:wrap">
            <?php if ($user['status'] !== 'suspended'): ?>
              <a href="../backend/api.php?action=suspend_user&id=<?= $user_id ?>" 
                 class="btn btn-danger" onclick="return confirm('Suspend this user?')">
                <i class="fas fa-ban"></i> Suspend User
              </a>
            <?php else: ?>
              <a href="../backend/api.php?action=unsuspend_user&id=<?= $user_id ?>" 
                 class="btn btn-success">
                <i class="fas fa-check"></i> Reactivate User
              </a>
            <?php endif; ?>

            <a href="#" class="btn btn-secondary">View All Listings</a>
            <a href="#" class="btn btn-secondary">View All Orders</a>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script src="../frontend/js/script.js"></script>
</body>
</html>