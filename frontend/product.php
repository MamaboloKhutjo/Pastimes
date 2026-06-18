<?php
session_start();
require_once '../backend/DBConn.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Fetch product details
$stmt = $conn->prepare("
    SELECT 
        cl.*,
        u.first_name,
        u.last_name,
        u.city as seller_city,
        u.username as seller_username
    FROM tblclothes cl
    JOIN tbluser u ON cl.seller_id = u.user_id
    WHERE cl.clothing_id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    echo "<h2 style='text-align:center;margin-top:50px;'>Product not found.</h2>";
    echo "<p style='text-align:center;'><a href='home.php'>← Back to Home</a></p>";
    exit();
}

// For price display
$price = (int)$product['price'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — <?= htmlspecialchars($product['title']) ?></title>
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
    <div class="product-detail-img">
      <img src="<?= htmlspecialchars($product['images'] ?? './assets/images/default.jpg') ?>" 
           alt="<?= htmlspecialchars($product['title']) ?>" id="mainImg">
    </div>
 
    <!-- Thumbnails (can be improved later with multiple images) -->
    <div class="product-thumbs">
      <div class="product-thumb active">
        <img src="<?= htmlspecialchars($product['images'] ?? './assets/images/default.jpg') ?>" alt="thumb 1">
      </div>
    </div>
 
    <!-- Product info -->
    <div class="product-detail-body">
      <div class="d-flex justify-between align-center mb-16">
        <h1 class="product-detail-title"><?= htmlspecialchars($product['title']) ?></h1>
        <span class="badge badge-rare">Rare Find</span>
      </div>
 
      <div class="d-flex align-center gap-12">
        <div class="product-detail-price">R <?= number_format($price, 0) ?></div>
        <span class="badge badge-approved">Verified Listing</span>
      </div>
 
      <!-- Seller card -->
      <div class="product-detail-seller">
        <div class="product-detail-seller-info">
          <img src="./assets/images/profile_man1.jpg" alt="Seller">
          <div>
            <div class="product-detail-seller-name">@<?= htmlspecialchars($product['seller_username'] ?? 'seller') ?></div>
            <div class="badge badge-approved" style="font-size:.65rem">Approved Seller</div>
            <div class="product-detail-seller-meta mt-8">
              ⭐ 4.9 · Cape Town
            </div>
          </div>
        </div>
        <button class="btn btn-secondary btn-sm" onclick="followSeller()">Follow</button>
      </div>
 
      <!-- Specs grid -->
      <div class="product-specs">
        <div class="spec-item">
          <div class="spec-label">Condition</div>
          <div class="spec-value"><?= htmlspecialchars($product['condition'] ?? 'Excellent Vintage') ?></div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Size</div>
          <div class="spec-value"><?= htmlspecialchars($product['size'] ?? 'N/A') ?></div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Material</div>
          <div class="spec-value"><?= htmlspecialchars($product['material'] ?? 'N/A') ?></div>
        </div>
        <div class="spec-item">
          <div class="spec-label">Ships From</div>
          <div class="spec-value"><?= htmlspecialchars($product['seller_city'] ?? 'South Africa') ?></div>
        </div>
      </div>
 
      <!-- Description -->
      <div class="product-detail-desc">
        <h4>About This Piece</h4>
        <p><?= nl2br(htmlspecialchars($product['description'] ?? 'No description available.')) ?></p>
      </div>
 
      <div style="height:100px"></div>
    </div>
 
    <!-- Sticky action bar -->
    <div class="product-actions-sticky">
      <a href="./messages.php" class="btn btn-secondary">
        <i class="fas fa-comment"></i> Message Seller
      </a>
      <button onclick="addToCart(<?= $product_id ?>)" class="btn btn-primary">
        Add to Cart · R <?= number_format($price, 0) ?>
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
  <script>
    function addToCart(clothingId) {
      if (!clothingId) return;

      fetch('../backend/cart_handler.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add_to_cart&clothing_id=${clothingId}&quantity=1`
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message || "✅ Added to cart!");
        } else {
          alert(data.message || "Failed to add to cart");
        }
      })
      .catch(() => {
        alert("Error connecting to server");
      });
    }

    function followSeller() {
      alert("Follow feature coming soon!");
    }
  </script>
</body>
</html>