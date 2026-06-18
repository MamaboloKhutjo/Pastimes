<?php
session_start();
require_once '../backend/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        c.cart_id,
        c.quantity,
        c.added_at,
        cl.clothing_id,
        cl.title,
        cl.price,
        cl.condition,
        cl.images,
        u.first_name,
        u.city
    FROM tblcart c
    JOIN tblclothes cl ON c.clothing_id = cl.clothing_id
    JOIN tbluser u ON cl.seller_id = u.user_id
    WHERE c.user_id = ?
    ORDER BY c.added_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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

<style>
/* --- ONLY CART ITEM UI IMPROVEMENTS --- */

.cart-item {
    display:flex;
    gap:14px;
    padding:14px;
    margin-bottom:14px;
    background:#fff;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    align-items:flex-start;
    position:relative;
}

.cart-item-img {
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:10px;
}

.cart-item-info {
    flex:1;
}

.cart-item-name {
    font-weight:600;
    font-size:1rem;
    margin-bottom:4px;
}

.cart-item-meta {
    font-size:.8rem;
    color:#777;
    margin-bottom:10px;
}

.cart-item-bottom {
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.cart-price {
    font-weight:700;
}

.qty-controls {
    display:flex;
    align-items:center;
    gap:10px;
}

.qty-controls button {
    width:28px;
    height:28px;
    border:none;
    background:#f2f2f2;
    border-radius:6px;
    cursor:pointer;
    font-weight:bold;
}

.qty-controls span {
    min-width:20px;
    text-align:center;
}

.line-total {
    font-size:.75rem;
    color:#666;
    margin-top:6px;
}

.delete-btn {
    position:absolute;
    top:10px;
    right:10px;
    background:none;
    border:none;
    color:#999;
    cursor:pointer;
}

.delete-btn:hover {
    color:red;
}
</style>

</head>

<body>
<div class="rainbow-bar"></div>

<div class="app-shell">

<header class="app-topbar" style="padding:12px 18px">
    <a href="./home.php" class="icon-btn">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="app-logo">Cart</div>

    <div style="font-size:.85rem;color:#777;font-weight:500">
        <?= count($cart_items) ?> items
    </div>
</header>

<div class="cart-layout">

<!-- LEFT SIDE -->
<div>

<h1 class="cart-title">Your Collection</h1>
<p class="cart-subtitle">Review your selected archive pieces</p>

<?php if (empty($cart_items)): ?>

    <div style="text-align:center;padding:80px 20px;color:#888;">
        <i class="fas fa-shopping-bag" style="font-size:70px;opacity:.2"></i>
        <p style="margin-top:12px;">Your cart is empty</p>

        <a href="./home.php" class="btn btn-primary" style="margin-top:10px;">
            Browse Archive
        </a>
    </div>

<?php else: ?>

    <?php foreach ($cart_items as $item): ?>

        <?php $lineTotal = $item['price'] * $item['quantity']; ?>

        <div class="cart-item">

            <img class="cart-item-img"
                 src="<?= htmlspecialchars($item['images'] ?? './assets/images/default.jpg') ?>">

            <div class="cart-item-info">

                <div class="cart-item-name">
                    <?= htmlspecialchars($item['title']) ?>
                </div>

                <div class="cart-item-meta">
                    <?= htmlspecialchars($item['condition']) ?> · 
                    <?= htmlspecialchars($item['first_name']) ?> · 
                    <?= htmlspecialchars($item['city']) ?>
                </div>

                <div class="cart-item-bottom">

                    <div class="cart-price">
                        R <?= number_format($item['price'], 0) ?>
                    </div>

                    <div class="qty-controls">
                        <button onclick="updateQty(<?= $item['cart_id'] ?>, -1)">−</button>
                        <span><?= $item['quantity'] ?></span>
                        <button onclick="updateQty(<?= $item['cart_id'] ?>, 1)">+</button>
                    </div>

                </div>

                <div class="line-total">
                    Subtotal: R <?= number_format($lineTotal, 0) ?>
                </div>

            </div>

            <button class="delete-btn" onclick="removeItem(<?= $item['cart_id'] ?>)">
                <i class="fas fa-trash"></i>
            </button>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

</div>

<!-- RIGHT SIDE (UNCHANGED CHECKOUT) -->
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

  <label class="delivery-option selected">
    <input type="radio" name="delivery" value="courier_guy" checked>
    <div class="delivery-option-info">
      <div class="delivery-option-name">Courier Guy</div>
      <div class="delivery-option-days">2–4 business days</div>
    </div>
    <div class="delivery-option-price">R 89</div>
  </label>

  <label class="delivery-option">
    <input type="radio" name="delivery" value="postnet">
    <div class="delivery-option-info">
      <div class="delivery-option-name">PostNet</div>
      <div class="delivery-option-days">4–7 business days</div>
    </div>
    <div class="delivery-option-price">R 55</div>
  </label>

  <div class="checkout-section-label" style="margin-top:16px">Promo Code</div>
  <div class="promo-row">
    <input class="form-control" type="text" placeholder="PASTTIMES10">
    <button class="btn btn-secondary btn-sm">Apply</button>
  </div>

  <div class="order-summary">
    <div class="summary-row">
      <span>Subtotal</span>
      <span>R <?= number_format($subtotal, 0) ?></span>
    </div>

    <div class="summary-row">
      <span>Delivery</span>
      <span>R <?= number_format($delivery_fee, 0) ?></span>
    </div>

    <div class="summary-row">
      <span>Buyer protection</span>
      <span class="text-success fw-600">Included</span>
    </div>

    <div class="summary-row total">
      <span>Total</span>
      <span>R <?= number_format($total, 0) ?></span>
    </div>
  </div>

  <form action="../backend/checkout_handler.php" method="POST">
    <button class="btn btn-primary btn-block btn-lg mt-16" type="submit">
      Place Order · EFT / Card
    </button>
  </form>
</div>

</div>

</div>

<script>
function updateQty(cartId, change) {
  fetch('../backend/cart_handler.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=update_quantity&cart_id=${cartId}&change=${change}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      location.reload();
    }
  });
}

function removeItem(cartId) {
  if (!confirm("Remove this item?")) return;

  fetch('../backend/cart_handler.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `action=remove_from_cart&cart_id=${cartId}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      location.reload();
    }
  });
}
</script>

</body>
</html>