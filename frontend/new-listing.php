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
      <button class="icon-btn" onclick="history.back()"><i class="fas fa-times"></i></button>
      <h1 class="listing-title">New Listing</h1>
      <!-- PHP: auto-save draft on input changes -->
      <button class="btn btn-secondary btn-sm" id="draftBtn" onclick="saveDraft()">Draft</button>
    </header>
 
    <div class="listing-body">
      <p style="text-align:center;font-style:italic;color:var(--clr-muted);margin-bottom:20px;font-size:.88rem">"Wear it again. Sell it again."</p>
 
      <!-- Approved seller banner -->
      <!-- PHP: if $user['seller_status'] === 'approved' -->
      <div class="listing-approved-banner">
        <i class="fas fa-shield-alt" style="color:var(--clr-success);margin-top:2px"></i>
        <div class="listing-approved-text">
          <!-- PHP: echo $user['full_name'] -->
          <strong>Approved Seller — Elena Vance</strong>
          <p>Your listings go live immediately after submission.</p>
        </div>
      </div>
      <!-- PHP: else: show pending review notice -->
 
      <!-- PHP: action="php/" method="POST" enctype="multipart/form-data" -->
      <form id="listingForm" action="php/" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create">
        <input type="hidden" name="draft_id" id="draftId" value="">
 
        <!-- Photo upload grid -->
        <div class="form-group">
          <label class="form-label">Visual Archive</label>
          <div class="photo-grid">
            <div class="photo-slot primary" id="primarySlot">
              <i class="fas fa-camera-plus photo-slot-icon"></i>
              <span class="photo-slot-label">Primary Image</span>
              <input type="file" name="images[]" accept="image/*" onchange="previewImage(event,'primarySlot')">
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
 
        <!-- Title -->
        <div class="form-group">
          <label class="form-label">Item Title</label>
          <input class="form-control" type="text" name="title" placeholder="e.g. 1990s Vintage Oversized Trench Coat" required>
        </div>
 
        <!-- Price & Condition -->
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
 
        <!-- Brand -->
        <div class="form-group">
          <label class="form-label">Brand / Designer</label>
          <input class="form-control" type="text" name="brand" placeholder="e.g. Woolworths, Stuttafords, Overseas Brand">
        </div>
 
        <!-- Size -->
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Size</label>
            <input class="form-control" type="text" name="size" placeholder="e.g. S, M, EU 40, 32W">
          </div>
          <div class="form-group">
            <label class="form-label">Material</label>
            <input class="form-control" type="text" name="material" placeholder="e.g. 100% Wool">
          </div>
        </div>
 
        <!-- City / Location -->
        <div class="form-group">
          <label class="form-label">City / Location</label>
          <!-- PHP: default to $user['city'] -->
          <input class="form-control" type="text" name="city" placeholder="Cape Town, WC" value="">
        </div>
 
        <!-- Category -->
        <div class="form-group">
          <label class="form-label">Category</label>
          <div class="category-chips" id="categoryChips">
            <div class="category-chip active" data-val="outerwear" onclick="setCategory(this)">Outerwear</div>
            <div class="category-chip" data-val="knitwear" onclick="setCategory(this)">Knitwear</div>
            <div class="category-chip" data-val="accessories" onclick="setCategory(this)">Accessories</div>
            <div class="category-chip" data-val="bottoms" onclick="setCategory(this)">Bottoms</div>
            <div class="category-chip" data-val="tops" onclick="setCategory(this)">Tops</div>
            <div class="category-chip" data-val="footwear" onclick="setCategory(this)">Footwear</div>
            <div class="category-chip" data-val="dresses" onclick="setCategory(this)">Dresses</div>
            <div class="category-chip" data-val="bags" onclick="setCategory(this)">Bags</div>
          </div>
          <input type="hidden" name="category" id="categoryInput" value="outerwear">
        </div>
 
        <!-- Description -->
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="4" placeholder="Tell the story of this piece — era, provenance, condition details..."></textarea>
        </div>
 
        <!-- Tags -->
        <div class="form-group">
          <label class="form-label">Tags</label>
          <input class="form-control" type="text" name="tags" placeholder="e.g. vintage, japanese, 1990s, structured">
          <small style="color:var(--clr-muted);font-size:.78rem;margin-top:4px">Comma-separated tags for search discovery</small>
        </div>
 
        <div style="height:24px"></div>
        <button class="btn btn-primary btn-block btn-lg" type="submit">Publish Listing</button>
        <div style="height:40px"></div>
      </form>
    </div>
 
  </div>
 
  <script src="../frontend/js/script.js"></script>
</body>
</html>