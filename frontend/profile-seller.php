<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Seller Profile</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">

    <!-- Top bar (seller specific) -->
    <header class="app-topbar" style="padding:12px 16px">
      <a href="./home.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
      <a href="./home.php" class="app-logo">Pastimes</a>
      <div style="display:flex; gap:12px;">
        <a href="./profile-seller.php?edit=1" class="icon-btn" title="Edit Profile"><i class="fas fa-edit"></i></a>
        <a href="../backend/logout.php" class="icon-btn" title="Logout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
    </header>
 
    <!-- Seller Profile header card -->
    <!-- PHP: $seller = getSellerProfile($_SESSION['user_id']) -->
    <div class="profile-header">
      <div class="profile-info">
        <div style="position:relative">
          <!-- PHP: echo $seller['profile_image'] -->
          <img class="profile-avatar" src="./assets/images/profile_woman2.jpg" alt="Elena Vance">
          <!-- PHP: if $seller['verified'] === 1 -->
          <div class="profile-verified"><i class="fas fa-check" style="font-size:.6rem"></i></div>
        </div>
        <div style="flex:1">
          <div class="d-flex align-center gap-8 mb-8">
            <!-- PHP: echo $seller['first_name'] . ' ' . $seller['last_name'] -->
            <div class="profile-name">Elena Vance</div>
            <span class="badge badge-approved">Seller</span>
          </div>
          <!-- PHP: echo '@seller_username · ' . $seller['city'] -->
          <div class="profile-handle">@the_archivist · Cape Town, WC</div>
          <!-- PHP: echo $seller['bio'] -->
          <div class="profile-bio">Curating mid-century silhouettes and Japanese textiles across SA. Every piece has a previous life.</div>
          <div class="profile-stats">
            <!-- PHP: count followers -->
            <span><strong>1.2k</strong> Followers</span>
            <!-- PHP: count following -->
            <span><strong>438</strong> Following</span>
            <!-- PHP: echo $seller_rating . ' (' . $seller_reviews . ')' -->
            <span>⭐ <strong>4.9</strong> (124)</span>
          </div>
        </div>
      </div>
 
      <div class="profile-actions">
        <!-- Seller-specific buttons -->
        <a href="./new-listing.php" class="btn btn-primary">+ New Listing</a>
        <button class="btn btn-secondary" onclick="location.href='./messages.php'">Messages</button>
      </div>
    </div>
 
    <!-- Seller Tabs -->
    <div class="profile-tabs">
      <!-- PHP: count active listings -->
      <div class="profile-tab active" data-tab="storefront" onclick="setTab(this,'storefront')">Storefront (24)</div>
      <!-- PHP: count sold items -->
      <div class="profile-tab" data-tab="sold" onclick="setTab(this,'sold')">Sold (12)</div>
      <div class="profile-tab" data-tab="reviews" onclick="setTab(this,'reviews')">Reviews</div>
      <div class="profile-tab" data-tab="stats" onclick="setTab(this,'stats')">Stats</div>
    </div>
 
    <!-- Storefront grid (seller listings) -->
    <!-- PHP: foreach $seller_listings as $l -->
    <div class="storefront-grid" id="storefrontGrid">
      <a href="./product.php?id=1" class="storefront-item">
        <img src="./assets/images/trench_coat.jpg" alt="Trench">
      </a>
      <a href="./product.php?id=2" class="storefront-item">
        <img src="./assets/images/leather_jacket.jpg" alt="Jacket">
        <div class="storefront-sold-badge">Sold</div>
      </a>
      <a href="./product.php?id=3" class="storefront-item">
        <img src="./assets/images/silk_blouse.jpg" alt="Blouse">
      </a>
      <a href="./product.php?id=4" class="storefront-item">
        <img src="./assets/images/silk_scarf.jpg" alt="Scarf">
      </a>
      <a href="./product.php?id=5" class="storefront-item">
        <img src="./assets/images/linen_coord.jpg" alt="Co-ord">
      </a>
      <a href="./product.php?id=6" class="storefront-item">
        <img src="./assets/images/trench_coat.jpg" alt="Coat">
      </a>
    </div>
    <!-- PHP: endforeach -->
 
    <!-- Sold tab -->
    <div id="soldGrid" style="display:none;padding:16px 20px;color:var(--clr-muted);font-style:italic;text-align:center">
      <!-- PHP: Load sold items here -->
      <p>No sold items yet</p>
    </div>
 
    <!-- Reviews tab (buyer reviews on seller) -->
    <div id="reviewsGrid" style="display:none;padding:20px">
      <div class="card mb-16">
        <div class="d-flex align-center gap-8 mb-8">
          <img src="./assets/images/profile_man2_small.jpg" style="width:36px;height:36px;border-radius:50%">
          <div>
            <strong>@marcus.v</strong>
            <div style="font-size:.75rem;color:var(--clr-muted)">⭐⭐⭐⭐⭐ · 14 March 2025</div>
          </div>
        </div>
        <p style="font-size:.9rem">Exceptional seller. The coat arrived beautifully wrapped with a handwritten note. Exactly as described.</p>
      </div>
    </div>
 
    <!-- Stats tab (seller-specific metrics) -->
    <div id="statsGrid" style="display:none;padding:20px">
      <div class="card mb-16">
        <h3 style="font-size:1rem;margin-bottom:16px">Performance Metrics</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:.9rem">
          <div style="padding:12px;background:var(--clr-surface);border-radius:8px">
            <div style="color:var(--clr-muted);margin-bottom:4px">Response Time</div>
            <div style="font-weight:600;font-size:1.1rem">2 hours</div>
          </div>
          <div style="padding:12px;background:var(--clr-surface);border-radius:8px">
            <div style="color:var(--clr-muted);margin-bottom:4px">Positive Feedback</div>
            <div style="font-weight:600;font-size:1.1rem">98%</div>
          </div>
          <div style="padding:12px;background:var(--clr-surface);border-radius:8px">
            <div style="color:var(--clr-muted);margin-bottom:4px">Total Sales</div>
            <div style="font-weight:600;font-size:1.1rem">R 45,280</div>
          </div>
          <div style="padding:12px;background:var(--clr-surface);border-radius:8px">
            <div style="color:var(--clr-muted);margin-bottom:4px">Items Sold</div>
            <div style="font-weight:600;font-size:1.1rem">32</div>
          </div>
        </div>
      </div>
    </div>
 
    <div style="height:80px"></div>
 
    <!-- Bottom nav -->
     <nav class="bottom-nav">
      <a href="./home.php" class="bottom-nav-item">
        <i class="fas fa-home nav-icon-lg"></i> Home
      </a>
      <a href="./search.php" class="bottom-nav-item">
        <i class="fas fa-search nav-icon-lg"></i> Search
      </a>
      <a href="./new-listing.php" class="bottom-nav-sell">
        <i class="fas fa-plus"></i>
      </a>
      <a href="./messages.php" class="bottom-nav-item">
        <i class="fas fa-comment nav-icon-lg"></i> Messages
      </a>
      <a href="./profile.php" class="bottom-nav-item active">
        <i class="fas fa-user nav-icon-lg"></i> Profile
      </a>
    </nav>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>
