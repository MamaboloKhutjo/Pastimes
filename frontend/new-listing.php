<?php
session_start();
require_once '../backend/DBConn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ./login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user is a seller from the database
$stmt = $conn->prepare("SELECT role, verified FROM tbluser WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user || $user['role'] !== 'seller') {
    header('Location: ./home.php');
    exit();
}

// Optional: Check if seller is verified
if ($user['verified'] != 1) {
    echo "<script>alert('Your seller account is still under review.'); window.location.href='./home.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — New Listing</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell" style="padding-bottom:0">
 
    <!-- Top bar -->
    <header class="listing-topbar">
      <a href="./home.php" class="icon-btn"><i class="fas fa-times"></i></a>
      <h1 class="listing-title">New Listing</h1>
      <button class="btn btn-secondary btn-sm" id="draftBtn" onclick="saveDraft()">Draft</button>
    </header>
 
    <div class="listing-body">
      <p style="text-align:center;font-style:italic;color:var(--clr-muted);margin-bottom:20px;font-size:.88rem">"Wear it again. Sell it again."</p>
 
      <!-- Approved seller banner -->
      <div class="listing-approved-banner">
        <i class="fas fa-shield-alt" style="color:var(--clr-success);margin-top:2px"></i>
        <div class="listing-approved-text">
          <strong>Approved Seller — <?= htmlspecialchars($_SESSION['full_name'] ?? 'Seller') ?></strong>
          <p>Your listings go live immediately after submission.</p>
        </div>
      </div>
 
      <form id="listingForm" action="../backend/listing_handler.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create_listing">

        <!-- Photo upload -->
        <div class="form-group">
          <label class="form-label">Visual Archive</label>
          <div class="photo-grid">
            <div class="photo-slot primary" id="primarySlot">
              <i class="fas fa-camera-plus photo-slot-icon"></i>
              <span class="photo-slot-label">Primary Image</span>
              <input type="file" name="images[]" accept="image/*" onchange="previewImage(event,'primarySlot')" required>
            </div>
            <div class="photo-slot" id="slot2">
              <i class="fas fa-plus photo-slot-icon"></i>
              <input type="file" name="images[]" accept="image/*" onchange="previewImage(event,'slot2')">
            </div>
            <div class="photo-slot" id="slot3">
              <i class="fas fa-plus photo-slot-icon"></i>
              <input type="file" name="images[]" accept="image/*" onchange="previewImage(event,'slot3')">
            </div>
          </div>
        </div>
 
        <!-- Rest of your form fields (unchanged) -->
        <div class="form-group">
          <label class="form-label">Item Title</label>
          <input class="form-control" type="text" name="title" placeholder="e.g. 1990s Vintage Oversized Trench Coat" required>
        </div>
 
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Price (ZAR)</label>
            <input class="form-control" type="number" name="price" placeholder="R 0.00" min="1" step="0.01" required>
          </div>
          <div class="form-group">
            <label class="form-label">Condition</label>
            <select class="form-control" name="condition" required>
              <option value="">Select...</option>
              <option>New with tags</option>
              <option>Excellent Vintage</option>
              <option>Very Good</option>
              <option>Good</option>
              <option>Fair</option>
            </select>
          </div>
        </div>
 
        <div class="form-group">
          <label class="form-label">Brand / Designer</label>
          <input class="form-control" type="text" name="brand" placeholder="e.g. Woolworths, Stuttafords...">
        </div>
 
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Size</label>
            <input class="form-control" type="text" name="size" placeholder="e.g. S, M, EU 40">
          </div>
          <div class="form-group">
            <label class="form-label">Material</label>
            <input class="form-control" type="text" name="material" placeholder="e.g. 100% Wool">
          </div>
        </div>
 
        <div class="form-group">
          <label class="form-label">City / Location</label>
          <input class="form-control" type="text" name="city" value="<?= htmlspecialchars($_SESSION['city'] ?? '') ?>" placeholder="Cape Town, WC">
        </div>
 
        <div class="form-group">
          <label class="form-label">Category</label>
          <div class="category-chips" id="categoryChips">
            <div class="category-chip active" data-val="outerwear" onclick="setCategory(this)">Outerwear</div>
            <div class="category-chip" data-val="knitwear" onclick="setCategory(this)">Knitwear</div>
            <div class="category-chip" data-val="accessories" onclick="setCategory(this)">Accessories</div>
            <!-- Add more as needed -->
          </div>
          <input type="hidden" name="category" id="categoryInput" value="outerwear">
        </div>
 
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="5" placeholder="Tell the story of this piece..."></textarea>
        </div>
 
        <div class="form-group">
          <label class="form-label">Tags</label>
          <input class="form-control" type="text" name="tags" placeholder="vintage, japanese, 1990s">
        </div>
 
        <div style="height:24px"></div>
        <button class="btn btn-primary btn-block btn-lg" type="submit">Publish Listing</button>
      </form>
    </div>
 
    <nav class="bottom-nav">
      <a href="./home.php" class="bottom-nav-item"><i class="fas fa-home nav-icon-lg"></i> Home</a>
      <a href="./search.php" class="bottom-nav-item"><i class="fas fa-search nav-icon-lg"></i> Search</a>
      <a href="./new-listing.php" class="bottom-nav-sell active"><i class="fas fa-plus"></i></a>
      <a href="./messages.php" class="bottom-nav-item"><i class="fas fa-comment nav-icon-lg"></i> Messages</a>
      <a href="./profile.php" class="bottom-nav-item"><i class="fas fa-user nav-icon-lg"></i> Profile</a>
    </nav>
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>