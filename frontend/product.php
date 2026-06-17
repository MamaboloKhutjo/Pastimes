<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Product Detail</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">
 
    <!-- Top bar -->
    <header class="app-topbar" style="padding:12px 16px">
      <a href="./home.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
      <a href="./home.php" class="app-logo">Pastimes</a>
      <div class="app-topbar-actions">
        <a href="./cart.php" class="icon-btn"><i class="fas fa-shopping-bag"></i></a>
        <div class="icon-btn"><i class="fas fa-ellipsis-h"></i></div>
      </div>
    </header>
 
    <!-- Product images -->
    <!-- PHP: $product loaded from DB by $_GET['id'] -->
    <div class="product-detail-img">
      <img src="#" alt="Product" id="mainImg">
    </div>
 
    <!-- Thumbnails -->
    <div class="product-thumbs">
      <div class="product-thumb active" onclick="setImg(this,'https://images.unsplash.com/photo-1594938298603-c8148c4b4a75?w=400')">
        <img src="#" alt="thumb 1">
      </div>
      <div class="product-thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1551803091-e20673f15770?w=400')">
        <img src="#" alt="thumb 2">
      </div>
      <div class="product-thumb" onclick="setImg(this,'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400')">
        <img src="#" alt="thumb 3">
      </div>
    </div>
 
    <!-- Product info -->
    <div class="product-detail-body">
      <div class="d-flex justify-between align-center mb-16">
        <!-- PHP: echo $product['title'] -->
        <h1 class="product-detail-title">1990s Italian Wool Oversized Blazer</h1>
        <span class="badge badge-rare">Rare Find</span>
      </div>
 
      <div class="d-flex align-center gap-12">
        <!-- PHP: echo 'R ' . number_format($product['price'], 0, '.', ' ') -->
        <div class="product-detail-price">R 3 200</div>
        <span class="badge badge-approved">Verified Listing</span>
      </div>
 
      <!-- Seller card -->
      <div class="product-detail-seller">
        <div class="product-detail-seller-info">
          <img src="#" alt="Seller">
          <div>
            <!-- PHP: echo $product['seller_username'] -->
            <div class="product-detail-seller-name">@the_curated_archive</div>
            <div class="badge badge-approved" style="font-size:.65rem">Approved Seller</div>
            <!-- PHP: echo $seller['rating'] . ' · ' . $seller['review_count'] . ' reviews · ' . $seller['city'] -->
            <div class="product-detail-seller-meta mt-8">⭐ 4.9 · 128 reviews · Cape Town</div>
          </div>
        </div>
        <button class="btn btn-secondary btn-sm" onclick="followSeller()">Follow</button>
      </div>
 
      <!-- Specs grid -->
      <div class="product-specs">
        <div class="spec-item">
          <div class="spec-label">Condition</div>
          <!-- PHP: echo $product['condition'] -->
          <div class="spec-value">Excellent Vintage</div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Size</div>
          <!-- PHP: echo $product['size'] -->
          <div class="spec-value">EU 40 / L</div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Material</div>
          <!-- PHP: echo $product['material'] -->
          <div class="spec-value">100% Wool</div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Ships From</div>
          <!-- PHP: echo $product['ships_from'] -->
          <div class="spec-value">Cape Town, WC</div>
        </div>
      </div>
 
      <!-- Description -->
      <div class="product-detail-desc">
        <h4>About This Piece</h4>
        <!-- PHP: echo nl2br(htmlspecialchars($product['description'])) -->
        <p>Beautiful charcoal grey blazer from the late 90s. Made in Italy from 100% virgin wool. Structured shoulders, three-button closure, silk lining. No stains, no damage — an exceptional find for anyone building an archival wardrobe.</p>
      </div>
 
      <!-- Spacer for sticky footer -->
      <div style="height:100px"></div>
    </div>
 
    <!-- Sticky action bar -->
    <div class="product-actions-sticky">
      <!-- PHP: action handled by backend to open conversation with seller -->
      <a href="./messages.php" class="btn btn-secondary">
        <i class="fas fa-comment"></i> Message
      </a>
      <!-- PHP: add to cart action -->
      <button class="btn btn-primary" onclick="addToCart()">
        Add to Cart · R 3 200
      </button>
    </div>
 
    <!-- Bottom nav -->
     <nav class="bottom-nav">
      <a href="./home.php" class="bottom-nav-item">
        <i class="fas fa-home nav-icon-lg"></i> Home
      </a>
      <a href="./search.php" class="bottom-nav-item active">
        <i class="fas fa-search nav-icon-lg"></i> Search
      </a>
      <a href="./new-listing.php" class="bottom-nav-sell">
        <i class="fas fa-plus"></i>
      </a>
      <a href="./messages.php" class="bottom-nav-item">
        <i class="fas fa-comment nav-icon-lg"></i> Messages
      </a>
      <a href="./profile.php" class="bottom-nav-item">
        <i class="fas fa-user nav-icon-lg"></i> Profile
      </a>
    </nav>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>