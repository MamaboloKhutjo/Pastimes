<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Search</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">

    <header class="app-topbar" style="padding:12px 20px">
      <a href="./home.php" class="icon-btn"><i class="fas fa-arrow-left"></i></a>
      <a href="./home.php" class="app-logo">Pastimes</a>
      <div style="font-size:.9rem;font-weight:500">Search</div>
    </header>

    <div style="padding:0 20px 24px">
      <div class="app-search" style="margin-top:16px">
        <span class="search-icon"><i class="fas fa-search"></i></span>
        <input class="form-control" id="searchInput" type="search" placeholder="Search archive, sellers, styles..." autocomplete="off" oninput="performSearch()">
      </div>

      <div id="searchResults" class="product-grid" style="display:none;margin-top:16px"></div>

      <p class="auth-tagline" style="margin:20px 0 10px">Start typing to explore curated finds, seller shops, and collections.</p>

      <div class="filter-pills">
        <div class="filter-pill active">Vintage</div>
        <div class="filter-pill">Designer</div>
        <div class="filter-pill">Outerwear</div>
        <div class="filter-pill">Accessories</div>
        <div class="filter-pill">Sustainable</div>
      </div>

      <h2 class="auth-heading" style="margin-top:24px;font-size:1.2rem">Recent Searches</h2>
      <div class="product-grid" style="margin-top:12px">
        <div class="product-card" onclick="location.href='./product.php'">
          <div class="product-card-img">
            <img src="./assets/images/leather_jacket.jpg" alt="Leather Jacket">
            <div class="product-card-heart" onclick="event.stopPropagation(); toggleHeart(event,this)"><i class="far fa-heart"></i></div>
          </div>
          <div class="product-card-info">
            <div class="product-card-title">1990s Leather Jacket</div>
            <div class="product-card-meta">Vintage · Size M</div>
            <div class="product-card-price">R 2 850</div>
          </div>
        </div>
        <div class="product-card" onclick="location.href='./product.php?id=2'">
          <div class="product-card-img">
            <img src="./assets/images/silk_skirt.jpg" alt="Silk Skirt">
            <div class="product-card-heart" onclick="event.stopPropagation(); toggleHeart(event,this)"><i class="far fa-heart"></i></div>
          </div>
          <div class="product-card-info">
            <div class="product-card-title">Silk Wrap Skirt</div>
            <div class="product-card-meta">New with tags · Size S</div>
            <div class="product-card-price">R 1 200</div>
          </div>
        </div>
        <div class="product-card" onclick="location.href='./product.php?id=3'">
          <div class="product-card-img">
            <img src="./assets/images/cashmere_sweater.jpg" alt="Cashmere Sweater">
            <div class="product-card-heart" onclick="event.stopPropagation(); toggleHeart(event,this)"><i class="far fa-heart"></i></div>
          </div>
          <div class="product-card-info">
            <div class="product-card-title">Cashmere Crewneck</div>
            <div class="product-card-meta">Luxury · Size L</div>
            <div class="product-card-price">R 3 400</div>
          </div>
        </div>
      </div>
    </div>

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