<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Home</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">
 
    <!-- Top bar -->
    <header class="app-topbar">
      <button class="icon-btn" id="menuBtn"><i class="fas fa-bars"></i></button>
      <div class="app-logo">Pastimes</div>
      <div class="app-search">
        <span class="search-icon"><i class="fas fa-search"></i></span>
        <!-- PHP: live search hits products table -->
        <input type="text" placeholder="Search archive..." id="searchInput" autocomplete="off" onfocus="location.href='./search.php'">
        <div id="searchResults" class="search-dropdown" style="display:none"></div>
      </div>
      <div class="app-topbar-actions">
        <div class="icon-btn">
          <i class="fas fa-shopping-bag"></i>
          <!-- PHP: echo cart item count from session -->
          <span class="badge-count">3</span>
        </div>
        <div class="icon-btn notif-btn">
          <i class="fas fa-bell"></i>
          <!-- PHP: echo unread notification count -->
          <span class="badge-count">5</span>
        </div>
        <div class="avatar-btn">
          <!-- PHP: echo user profile image src -->
          <img src="#" alt="Profile">
        </div>
      </div>
    </header>
 
    <!-- Stories / Following row -->
    <div class="stories-row">
      <div class="story-item active">
        <div class="story-ring">
          <img src="#" alt="Your edit">
        </div>
        <span class="story-label">Your Edit</span>
      </div>
      <!-- PHP: foreach $following_sellers as $s -->
      <div class="story-item">
        <div class="story-ring"><img src="#" alt="marcus.v"></div>
        <span class="story-label">marcus.v</span>
      </div>
      <div class="story-item">
        <div class="story-ring"><img src="#" alt="eliza_s"></div>
        <span class="story-label">eliza_s</span>
      </div>
      <div class="story-item">
        <div class="story-ring"><img src="#" alt="curated_j"></div>
        <span class="story-label">curated_j</span>
      </div>
      <div class="story-item">
        <div class="story-ring"><img src="#" alt="sara.h"></div>
        <span class="story-label">sara.h</span>
      </div>
      <div class="story-item">
        <div class="story-ring"><img src="#" alt="theo_v"></div>
        <span class="story-label">theo_v</span>
      </div>
      <!-- PHP: endforeach -->
    </div>
 
    <!-- Location search bar -->
    <div style="padding:0 20px 8px">
      <div class="app-search" style="max-width:100%">
        <span class="search-icon"><i class="fas fa-search"></i></span>
        <input type="text" placeholder="Search Cape Town, JHB, Durban archive..." style="background:var(--clr-surface)">
      </div>
    </div>
 
    <!-- Filter pills -->
    <!-- PHP: active filter passed via $_GET['filter'] -->
    <div class="filter-pills" id="filterPills">
      <div class="filter-pill active" data-filter="for_you" onclick="setFilter('for_you',this)">For You</div>
      <div class="filter-pill" data-filter="trending_za" onclick="setFilter('trending_za',this)">Trending ZA</div>
      <div class="filter-pill" data-filter="cape_town" onclick="setFilter('cape_town',this)">Cape Town</div>
      <div class="filter-pill" data-filter="jhb" onclick="setFilter('jhb',this)">JHB</div>
      <div class="filter-pill" data-filter="rare_finds" onclick="setFilter('rare_finds',this)">Rare Finds</div>
      <div class="filter-pill" data-filter="sustainable" onclick="setFilter('sustainable',this)">Sustainable</div>
    </div>
 
    <!-- Product grid -->
    <!-- PHP: foreach $products as $p — render product cards -->
    <div class="product-grid" id="productGrid">
 
      <!-- Hero / editorial pick -->
      <div class="product-card hero" onclick="location.href='product.html'">
        <div class="product-card-img">
          <img src="#" alt="Burberry Trench">
          <div class="product-card-badge editorial">Editorial Pick</div>
          <div class="product-card-heart" onclick="toggleHeart(event,this)"><i class="far fa-heart"></i></div>
          <div class="product-card-overlay">
            <div class="title">1970s Burberry Trench — Cape Town</div>
            <div class="price">R 4 200</div>
            <div class="seller">
              <img src="#" alt="seller">
              @the_archivist
            </div>
          </div>
        </div>
      </div>
 
      <!-- Standard cards -->
      <div class="product-card" onclick="location.href='./product.php'">
        <div class="product-card-img">
          <img src="#" alt="Silk Wrap Blouse">
          <div class="product-card-heart" onclick="toggleHeart(event,this)"><i class="far fa-heart"></i></div>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Silk Wrap Blouse</div>
          <div class="product-card-meta">Vintage · Size S</div>
          <div class="product-card-price">R 1 500</div>
        </div>
      </div>
 
      <div class="product-card" onclick="location.href='./product.php'">
        <div class="product-card-img">
          <img src="#" alt="Japanese Denim Jacket">
          <div class="product-card-badge rare">Rare Find</div>
          <div class="product-card-heart" onclick="toggleHeart(event,this)"><i class="far fa-heart"></i></div>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Japanese Denim Jacket</div>
          <div class="product-card-meta">Kapital · Size L · JHB</div>
          <div class="product-card-price">R 5 500</div>
        </div>
      </div>
 
      <div class="product-card" onclick="location.href='product.html'">
        <div class="product-card-img">
          <img src="#" alt="Linen Co-ord Set">
          <div class="product-card-heart" onclick="toggleHeart(event,this)"><i class="far fa-heart"></i></div>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Linen Co-ord Set</div>
          <div class="product-card-meta">Sustainable · Size XS · JHB</div>
          <div class="product-card-price">R 1 900</div>
        </div>
      </div>
 
      <div class="product-card" onclick="location.href='product.html'">
        <div class="product-card-img">
          <img src="#" alt="Archive Silk Scarf">
          <div class="product-card-heart" onclick="toggleHeart(event,this)"><i class="far fa-heart"></i></div>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Archive Silk Scarf</div>
          <div class="product-card-meta">Accessories · Vintage · Durban</div>
          <div class="product-card-price">R 950</div>
        </div>
      </div>
 
    </div>
    <!-- PHP: load more via AJAX or pagination -->
 
    <!-- Bottom nav -->
    <nav class="bottom-nav">
      <div class="bottom-nav-item active" onclick="location.href='./home.php'">
        <i class="fas fa-home nav-icon-lg"></i> Home
      </div>
      <div class="bottom-nav-item" onclick="location.href='./search.php'">
        <i class="fas fa-search nav-icon-lg"></i> Search
      </div>
      <div class="bottom-nav-sell" onclick="location.href='./new-listing.php'" title="Sell">
        <i class="fas fa-plus"></i>
      </div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:4px;font-size:.65rem;font-weight:500;letter-spacing:.06em;text-transform:uppercase;color:var(--clr-muted);cursor:pointer" onclick="location.href='./messages.php'">
        <i class="fas fa-comment nav-icon-lg" style="font-size:1.25rem"></i> Messages
      </div>
      <div class="bottom-nav-item" onclick="location.href='./profile.php'">
        <i class="fas fa-user nav-icon-lg"></i> Profile
      </div>
    </nav>
 
  </div>
 
<script src="../frontend/js/script.js"></script>
</body>
</html>