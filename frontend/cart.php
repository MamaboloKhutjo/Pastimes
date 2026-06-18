<?php
session_start();
require_once '../backend/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items with safe query
$cart_query = $conn->query("
    SELECT 
        c.cart_id,
        c.quantity,
        cl.clothing_id,
        cl.title,
        cl.price,
        cl.condition,
        u.first_name,
        u.last_name,
        u.city
    FROM tblcart c
    JOIN tblclothes cl ON c.clothing_id = cl.clothing_id
    JOIN tbluser u ON cl.seller_id = u.user_id
    WHERE c.user_id = $user_id
    ORDER BY c.added_at DESC
");

$cart_items = $cart_query ? $cart_query->fetch_all(MYSQLI_ASSOC) : [];

$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = 89;
$total = $subtotal + $delivery_fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Cart</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">
 
    <header class="app-topbar" style="padding:12px 20px">
      <a href="./home.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
      <a href="./home.php" class="app-logo">Pastimes</a>
      <div style="font-size:.9rem;font-weight:500">Your Cart (<?= count($cart_items) ?>)</div>
    </header>
 
    <div class="cart-layout">

      <div>
        <h1 class="cart-title">Review Cart</h1>
        <p class="cart-subtitle"><?= count($cart_items) ?> items curated for your collection.</p>

        <?php if (empty($cart_items)): ?>
          <div style="text-align:center; padding:80px 20px; color:#888;">
            <i class="fas fa-shopping-bag" style="font-size:60px; margin-bottom:20px; opacity:0.4"></i>
            <p>Your cart is empty</p>
            <a href="./home.php" class="btn btn-primary">Browse Items</a>
          </div>
        <?php else: ?>
          <?php foreach ($cart_items as $item): ?>
            <div class="cart-item" data-cart-id="<?= $item['cart_id'] ?>">
              <div class="cart-item-img">
                <img src="<?= htmlspecialchars($item['images'] ?? './assets/images/default.jpg') ?>" 
                     alt="<?= htmlspecialchars($item['title']) ?>">
              </div>
              <div class="cart-item-info">
                <div class="cart-item-name"><?= htmlspecialchars($item['title']) ?></div>
                <div class="cart-item-meta">
                  <?= htmlspecialchars($item['condition'] ?? '') ?> · 
                  <?= htmlspecialchars($item['first_name'] ?? '') ?> · 
                  <?= htmlspecialchars($item['city'] ?? '') ?>
                </div>
                <div class="cart-item-qty">
                  <div class="cart-item-price">R <?= number_format($item['price'], 0) ?></div>
                  <div style="display:flex;align-items:center;gap:8px;margin-left:auto">
                    <button class="qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, -1)">−</button>
                    <span class="qty-display"><?= $item['quantity'] ?></span>
                    <button class="qty-btn" onclick="updateQty(<?= $item['cart_id'] ?>, 1)">+</button>
                  </div>
                </div>
              </div>
              <button class="cart-item-delete" onclick="removeItem(<?= $item['cart_id'] ?>)">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Checkout Panel (same as before) -->
      <div class="checkout-card">
        <h2 class="checkout-title">Checkout</h2>

        <div class="checkout-section-label">Delivery Address</div>
        <div class="address-box">
          <div>
            <div class="address-name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Your Name') ?></div>
            <div class="address-street">Update in your profile</div>
          </div>
          <a class="text-primary fw-600 fs-sm" href="./profile.php">Edit</a>
        </div>

        <div class="checkout-section-label">Delivery Method</div>
        <label class="delivery-option selected" onclick="selectDelivery(this,'courier_guy',89)">
          <input type="radio" name="delivery" value="courier_guy" checked>
          <div class="delivery-option-info">
            <div class="delivery-option-name">Courier Guy</div>
            <div class="delivery-option-days">2–4 business days</div>
          </div>
          <div class="delivery-option-price">R 89</div>
        </label>

        <label class="delivery-option" onclick="selectDelivery(this,'postnet',55)">
          <input type="radio" name="delivery" value="postnet">
          <div class="delivery-option-info">
            <div class="delivery-option-name">PostNet</div>
            <div class="delivery-option-days">4–7 business days</div>
          </div>
          <div class="delivery-option-price">R 55</div>
        </label>

        <div class="checkout-section-label" style="margin-top:16px">Promo Code</div>
        <div class="promo-row">
          <input class="form-control" type="text" id="promoInput" placeholder="PASTTIMES10">
          <button class="btn btn-secondary btn-sm" onclick="applyPromo()">Apply</button>
        </div>

        <div class="order-summary">
          <div class="summary-row">
            <span>Subtotal</span>
            <span id="subtotal">R <?= number_format($subtotal, 0) ?></span>
          </div>
          <div class="summary-row">
            <span>Delivery</span>
            <span id="deliveryFee">R <?= number_format($delivery_fee, 0) ?></span>
          </div>
          <div class="summary-row">
            <span>Buyer protection</span>
            <span class="text-success fw-600">Included</span>
          </div>
          <div class="summary-row total">
            <span>Total</span>
            <span class="price" id="totalPrice">R <?= number_format($total, 0) ?></span>
          </div>
        </div>

        <form action="../backend/checkout_handler.php" method="POST">
          <button class="btn btn-primary btn-block btn-lg mt-16" type="submit">
            Place Order · EFT / Card
          </button>
        </form>
      </div>
    </div>

    <nav class="bottom-nav">
      <a href="./home.php" class="bottom-nav-item"><i class="fas fa-home nav-icon-lg"></i> Home</a>
      <a href="./search.php" class="bottom-nav-item active"><i class="fas fa-search nav-icon-lg"></i> Search</a>
      <a href="./new-listing.php" class="bottom-nav-sell"><i class="fas fa-plus"></i></a>
      <a href="./messages.php" class="bottom-nav-item"><i class="fas fa-comment nav-icon-lg"></i> Messages</a>
      <a href="./profile.php" class="bottom-nav-item"><i class="fas fa-user nav-icon-lg"></i> Profile</a>
    </nav>
  </div>

  <script src="../frontend/js/script.js"></script>
  <script>
    function updateQty(cartId, change) {
      fetch('../backend/cart_handler.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=update_quantity&cart_id=${cartId}&quantity=${change}`
      }).then(() => location.reload());
    }

    function removeItem(cartId) {
      if (confirm('Remove this item?')) {
        fetch('../backend/cart_handler.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: `action=remove_from_cart&cart_id=${cartId}`
        }).then(() => location.reload());
      }
    }

    function selectDelivery(el, method, fee) {
      document.querySelectorAll('.delivery-option').forEach(o => o.classList.remove('selected'));
      el.classList.add('selected');
      document.getElementById('deliveryFee').textContent = 'R ' + fee;
    }

    function applyPromo() {
      alert("Promo code feature coming soon!");
    }
  </script>
</body>
</html>