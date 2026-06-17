<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Buyer Profile</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">

    <!-- Top bar (buyer specific) -->
    <header class="app-topbar" style="padding:12px 16px">
      <a href="./home.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
      <a href="./home.php" class="app-logo">Pastimes</a>
      <div style="display:flex; gap:12px;">
        <a href="./profile-buyer.php?edit=1" class="icon-btn" title="Edit Profile"><i class="fas fa-edit"></i></a>
        <a href="../backend/logout.php" class="icon-btn" title="Logout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </div>
    </header>
 
    <!-- Buyer Profile header card -->
    <!-- PHP: $buyer = getBuyerProfile($_SESSION['user_id']) -->
    <div class="profile-header">
      <div class="profile-info">
        <div style="position:relative">
          <!-- PHP: echo $buyer['profile_image'] -->
          <img class="profile-avatar" src="./assets/images/profile_man1.jpg" alt="John Smith">
        </div>
        <div style="flex:1">
          <div class="d-flex align-center gap-8 mb-8">
            <!-- PHP: echo $buyer['first_name'] . ' ' . $buyer['last_name'] -->
            <div class="profile-name">John Smith</div>
            <span class="badge badge-buyer">Buyer</span>
          </div>
          <!-- PHP: echo '@buyer_username · ' . $buyer['city'] -->
          <div class="profile-handle">@vintagelover · Johannesburg, GP</div>
          <!-- PHP: echo $buyer['bio'] -->
          <div class="profile-bio">Collector of rare vintage finds. Always searching for the next treasure.</div>
          <div class="profile-stats">
            <!-- PHP: count purchases -->
            <span><strong>18</strong> Purchases</span>
            <!-- PHP: count followed sellers -->
            <span><strong>24</strong> Following</span>
            <!-- PHP: echo $buyer_rating . ' (' . $buyer_reviews . ')' -->
            <span>⭐ <strong>4.7</strong> (18)</span>
          </div>
        </div>
      </div>
 
      <div class="profile-actions">
        <!-- Buyer-specific buttons -->
        <a href="./search.php" class="btn btn-primary">Browse Items</a>
        <button class="btn btn-secondary" onclick="location.href='./messages.php'">Messages</button>
      </div>
    </div>
 
    <!-- Buyer Tabs -->
    <div class="profile-tabs">
      <!-- PHP: count wishlist items -->
      <div class="profile-tab active" data-tab="wishlist" onclick="setTab(this,'wishlist')">Wishlist (12)</div>
      <!-- PHP: count purchases -->
      <div class="profile-tab" data-tab="purchases" onclick="setTab(this,'purchases')">Purchases (18)</div>
      <div class="profile-tab" data-tab="reviews" onclick="setTab(this,'reviews')">Reviews</div>
      <div class="profile-tab" data-tab="activity" onclick="setTab(this,'activity')">Activity</div>
    </div>
 
    <!-- Wishlist grid -->
    <!-- PHP: foreach $wishlist_items as $w -->
    <div class="storefront-grid" id="wishlistGrid">
      <a href="./product.php?id=1" class="storefront-item">
        <img src="./assets/images/trench_coat.jpg" alt="Trench">
        <div style="position:absolute;top:8px;right:8px;background:var(--clr-primary);color:white;padding:4px 8px;border-radius:4px;font-size:.7rem">❤️</div>
      </a>
      <a href="./product.php?id=2" class="storefront-item">
        <img src="./assets/images/leather_jacket.jpg" alt="Jacket">
        <div style="position:absolute;top:8px;right:8px;background:var(--clr-primary);color:white;padding:4px 8px;border-radius:4px;font-size:.7rem">❤️</div>
      </a>
      <a href="./product.php?id=3" class="storefront-item">
        <img src="./assets/images/silk_blouse.jpg" alt="Blouse">
        <div style="position:absolute;top:8px;right:8px;background:var(--clr-primary);color:white;padding:4px 8px;border-radius:4px;font-size:.7rem">❤️</div>
      </a>
      <a href="./product.php?id=4" class="storefront-item">
        <img src="./assets/images/silk_scarf.jpg" alt="Scarf">
        <div style="position:absolute;top:8px;right:8px;background:var(--clr-primary);color:white;padding:4px 8px;border-radius:4px;font-size:.7rem">❤️</div>
      </a>
    </div>
    <!-- PHP: endforeach -->
 
    <!-- Purchases tab -->
    <div id="purchasesGrid" style="display:none;padding:20px">
      <!-- PHP: foreach $purchases as $p -->
      <div class="card mb-16">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
          <div>
            <div style="font-weight:600">1970s Burberry Trench</div>
            <div style="font-size:.85rem;color:var(--clr-muted)">from @the_archivist</div>
          </div>
          <div style="text-align:right">
            <div style="font-weight:600">R 4,200</div>
            <div style="font-size:.8rem;color:var(--clr-success)">✓ Delivered</div>
          </div>
        </div>
        <div style="display:flex;gap:8px">
          <a href="./product.php?id=1" class="btn btn-secondary btn-sm">View Item</a>
          <a href="./messages.php" class="btn btn-secondary btn-sm">Message Seller</a>
        </div>
      </div>
      <div class="card mb-16">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
          <div>
            <div style="font-weight:600">Japanese Denim Jacket</div>
            <div style="font-size:.85rem;color:var(--clr-muted)">from @marcus.v</div>
          </div>
          <div style="text-align:right">
            <div style="font-weight:600">R 5,500</div>
            <div style="font-size:.8rem;color:var(--clr-warning)">📦 In Transit</div>
          </div>
        </div>
        <div style="display:flex;gap:8px">
          <a href="./product.php?id=2" class="btn btn-secondary btn-sm">View Item</a>
          <a href="./messages.php" class="btn btn-secondary btn-sm">Track Order</a>
        </div>
      </div>
      <!-- PHP: endforeach -->
    </div>
 
    <!-- Reviews tab (buyer reviews on purchases) -->
    <div id="reviewsGrid" style="display:none;padding:20px">
      <div class="card mb-16">
        <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:12px">
          <div style="flex:1">
            <div style="font-weight:600">Great seller!</div>
            <div style="font-size:.85rem;color:var(--clr-muted);margin-bottom:8px">Review for 1970s Burberry Trench</div>
            <div>⭐⭐⭐⭐⭐</div>
          </div>
          <div style="font-size:.8rem;color:var(--clr-muted)">2 weeks ago</div>
        </div>
        <p style="font-size:.9rem;color:var(--clr-muted)">Excellent condition, exactly as described. Fast shipping and great packaging.</p>
      </div>
    </div>
 
    <!-- Activity tab (recent activities) -->
    <div id="activityGrid" style="display:none;padding:20px">
      <div class="card mb-8">
        <div style="display:flex;gap:12px;font-size:.9rem">
          <div style="color:var(--clr-primary);font-weight:600">❤️</div>
          <div>
            <span style="font-weight:500">You liked</span> <a href="#" style="color:var(--clr-primary);text-decoration:none">Japanese Denim Jacket</a>
            <div style="font-size:.8rem;color:var(--clr-muted);margin-top:4px">2 hours ago</div>
          </div>
        </div>
      </div>
      <div class="card mb-8">
        <div style="display:flex;gap:12px;font-size:.9rem">
          <div style="color:var(--clr-primary);font-weight:600">🛒</div>
          <div>
            <span style="font-weight:500">You added to cart</span> <a href="#" style="color:var(--clr-primary);text-decoration:none">Silk Wrap Blouse</a>
            <div style="font-size:.8rem;color:var(--clr-muted);margin-top:4px">1 day ago</div>
          </div>
        </div>
      </div>
      <div class="card mb-8">
        <div style="display:flex;gap:12px;font-size:.9rem">
          <div style="color:var(--clr-primary);font-weight:600">👤</div>
          <div>
            <span style="font-weight:500">You followed</span> <a href="#" style="color:var(--clr-primary);text-decoration:none">@marcus.v</a>
            <div style="font-size:.8rem;color:var(--clr-muted);margin-top:4px">3 days ago</div>
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
