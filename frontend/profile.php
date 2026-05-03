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

    <!-- Top bar (added logout icon) -->
    <header class="app-topbar" style="padding:12px 16px">
      <button class="icon-btn" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
      <div class="app-logo">Pastimes</div>
      <div style="display:flex; gap:12px;">
        <div class="icon-btn" onclick="location.href='../backend/logout.php'">
          <i class="fas fa-sign-out-alt"></i>
        </div>
        <div class="icon-btn"><i class="fas fa-share-alt"></i></div>
      </div>
    </header>
 
    <!-- Profile header card -->
    <!-- PHP: $seller = getSellerProfile($_GET['username'] ?? $_SESSION['username']) -->
    <div class="profile-header">
      <div class="profile-info">
        <div style="position:relative">
          <!-- PHP: echo $seller['profile_image'] -->
          <img class="profile-avatar" src="./assets/images/profile_woman2.jpg" alt="Elena Vance">
          <!-- PHP: if $seller['status'] === 'approved' -->
          <div class="profile-verified"><i class="fas fa-check" style="font-size:.6rem"></i></div>
        </div>
        <div style="flex:1">
          <div class="d-flex align-center gap-8 mb-8">
            <!-- PHP: echo $seller['full_name'] -->
            <div class="profile-name">Elena Vance</div>
            <span class="badge badge-approved">Approved Seller</span>
          </div>
          <!-- PHP: echo '@' . $seller['username'] . ' · ' . $seller['city'] -->
          <div class="profile-handle">@the_archivist · Cape Town, WC</div>
          <!-- PHP: echo $seller['bio'] -->
          <div class="profile-bio">Curating mid-century silhouettes and Japanese textiles across SA. Every piece has a previous life.</div>
          <div class="profile-stats">
            <!-- PHP: echo $seller['follower_count'] -->
            <span><strong>1.2k</strong> Followers</span>
            <!-- PHP: echo $seller['following_count'] -->
            <span><strong>438</strong> Following</span>
            <!-- PHP: echo $seller['rating'] . ' (' . $seller['review_count'] . ')' -->
            <span>⭐ <strong>4.9</strong> (124)</span>
          </div>
        </div>
      </div>
 
      <div class="profile-actions">
        <!-- PHP: if viewing own profile show Edit, else show Follow/Message -->
        <button class="btn btn-primary" onclick="toggleFollow(this)">Follow</button>
        <button class="btn btn-secondary" onclick="location.href='./messages.php'">Message</button>
      </div>
    </div>
 
    <!-- Tabs -->
    <div class="profile-tabs">
      <!-- PHP: echo $seller['listing_count'] -->
      <div class="profile-tab active" data-tab="storefront" onclick="setTab(this,'storefront')">Storefront (24)</div>
      <!-- PHP: echo $seller['sold_count'] -->
      <div class="profile-tab" data-tab="sold" onclick="setTab(this,'sold')">Sold (12)</div>
      <div class="profile-tab" data-tab="reviews" onclick="setTab(this,'reviews')">Reviews</div>
      <div class="profile-tab" data-tab="about" onclick="setTab(this,'about')">About</div>
    </div>
 
    <!-- Storefront grid -->
    <!-- PHP: foreach $seller['listings'] as $l -->
    <div class="storefront-grid" id="storefrontGrid">
      <div class="storefront-item" onclick="location.href='./product.php'">
        <img src="./assets/images/trench_coat.jpg" alt="Trench">
      </div>
      <div class="storefront-item" onclick="location.href='./product.php?id=2'">
        <img src="./assets/images/leather_jacket.jpg" alt="Jacket">
        <div class="storefront-sold-badge">Sold</div>
      </div>
      <div class="storefront-item" onclick="location.href='./product.php?id=3'">
        <img src="./assets/images/silk_blouse.jpg" alt="Blouse">
      </div>
      <div class="storefront-item" onclick="location.href='./product.php?id=4'">
        <img src="./assets/images/silk_scarf.jpg" alt="Scarf">
      </div>
      <div class="storefront-item" onclick="location.href='./product.php?id=5'">
        <img src="./assets/images/linen_coord.jpg" alt="Co-ord">
      </div>
      <div class="storefront-item" onclick="location.href='./product.php?id=6'">
        <img src="./assets/images/trench_coat.jpg" alt="Coat">
      </div>
    </div>
    <!-- PHP: endforeach; load_more pagination -->
 
    <!-- Sold tab (hidden by default) -->
    <div id="soldGrid" style="display:none;padding:16px 20px;color:var(--clr-muted);font-style:italic;text-align:center">
      PHP: Load sold items here
    </div>
 
    <!-- Reviews tab -->
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
 
    <!-- About tab -->
    <div id="aboutGrid" style="display:none;padding:20px">
      <div class="card">
        <h3 style="font-size:1rem;margin-bottom:12px">About Elena</h3>
        <!-- PHP: echo $seller['about_long'] -->
        <p style="font-size:.9rem;line-height:1.7;color:var(--clr-muted)">Based in Cape Town since 2018, Elena has been sourcing archival pieces from estate sales, vintage markets, and international buying trips. Specialising in Japanese textiles and mid-century European fashion.</p>
      </div>
    </div>
 
    <div style="height:80px"></div>
 
    <!-- Bottom nav -->
     <nav class="bottom-nav">
      <div class="bottom-nav-item" onclick="location.href='./home.php'">
        <i class="fas fa-home nav-icon-lg"></i> Home
      </div>
      <div class="bottom-nav-item active" onclick="location.href='./search.php'">
        <i class="fas fa-search nav-icon-lg"></i> Search
      </div>
      <div class="bottom-nav-sell" onclick="location.href='./new-listing.php'">
        <i class="fas fa-plus"></i>
      </div>
      <div class="bottom-nav-item" onclick="location.href='./messages.php'">
        <i class="fas fa-comment nav-icon-lg"></i> Messages
      </div>
      <div class="bottom-nav-item" onclick="location.href='./profile.php'">
        <i class="fas fa-user nav-icon-lg"></i> Profile
      </div>
    </nav>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>