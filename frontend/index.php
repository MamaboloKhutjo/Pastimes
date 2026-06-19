<?php
// Redirect logged-in users to their dashboard
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: frontend/home.php");
    exit();
}
if (isset($_SESSION['admin_id'])) {
    header("Location: admin/admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pastimes — South Africa's Preloved Fashion Marketplace</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <div class="rainbow-bar"></div>

  <!-- NAV -->
  <nav class="landing-nav">
    <div class="landing-nav-logo">Pastimes</div>
    <div class="landing-nav-links">
      <a href="frontend/browse.php">Browse</a>
      <a href="#how">How it works</a>
      <a href="frontend/register.php?role=seller">Sell</a>
      <a href="#about">About</a>
    </div>
    <div class="landing-nav-actions">
      <a href="login.php" class="btn btn-secondary btn-sm">Log in</a>
      <a href="register.php" class="btn btn-primary btn-sm">Sign up free</a>
    </div>
  </nav>

  <!-- TICKER -->
  <div class="ticker-wrap">
    <div class="ticker-track">
      <span>Vintage</span><span class="sep">·</span>
      <span>Y2K</span><span class="sep">·</span>
      <span>Streetwear</span><span class="sep">·</span>
      <span>Archive Pieces</span><span class="sep">·</span>
      <span>Designer Finds</span><span class="sep">·</span>
      <span>Preloved</span><span class="sep">·</span>
      <span>SA Brands</span><span class="sep">·</span>
      <span>Slow Fashion</span><span class="sep">·</span>
      <span>Thrift Gems</span><span class="sep">·</span>
      <span>Curated Lots</span><span class="sep">·</span>
      <!-- duplicate for seamless loop -->
      <span>Vintage</span><span class="sep">·</span>
      <span>Y2K</span><span class="sep">·</span>
      <span>Streetwear</span><span class="sep">·</span>
      <span>Archive Pieces</span><span class="sep">·</span>
      <span>Designer Finds</span><span class="sep">·</span>
      <span>Preloved</span><span class="sep">·</span>
      <span>SA Brands</span><span class="sep">·</span>
      <span>Slow Fashion</span><span class="sep">·</span>
      <span>Thrift Gems</span><span class="sep">·</span>
      <span>Curated Lots</span><span class="sep">·</span>
    </div>
  </div>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-left">
      <div class="hero-eyebrow">South Africa's Preloved Fashion Marketplace</div>
      <h1 class="hero-title">
        Every piece has<br>
        a story.<br>
        <em>Find yours.</em>
      </h1>
      <p class="hero-sub">
        Pastimes connects South African fashion lovers — buy one-of-a-kind preloved clothing directly from local sellers, or give your wardrobe a second life.
      </p>
      <div class="hero-actions">
        <a href="login.php" class="btn btn-primary btn-lg">Shop the archive</a>
        <a href="register.php?role=seller" class="btn btn-outline-primary btn-lg">Start selling</a>
      </div>
      <div class="hero-trust">
        <div class="trust-item">
          <strong>2 400+</strong>
          <small>Listings</small>
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <strong>18</strong>
          <small>Cities across SA</small>
        </div>
        <div class="trust-sep"></div>
        <div class="trust-item">
          <strong>Verified</strong>
          <small>Sellers only</small>
        </div>
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-img" style="background-image:url('frontend/assets/images/hero_img.jpg')"></div>
    </div>
  </section>

  <!-- CATEGORIES -->
  <section class="landing-section alt">
    <div class="section-eyebrow">Browse by category</div>
    <h2 class="section-heading">Shop what you <em>actually</em> want</h2>
    <div class="cat-grid">
      <a href="frontend/browse.php?category=jackets" class="cat-card">
        <span class="cat-icon">🧥</span>
        <div class="cat-name">Jackets & Coats</div>
        <div class="cat-count">184 listings</div>
      </a>
      <a href="frontend/browse.php?category=dresses" class="cat-card">
        <span class="cat-icon">👗</span>
        <div class="cat-name">Dresses</div>
        <div class="cat-count">312 listings</div>
      </a>
      <a href="frontend/browse.php?category=sneakers" class="cat-card">
        <span class="cat-icon">👟</span>
        <div class="cat-name">Sneakers</div>
        <div class="cat-count">207 listings</div>
      </a>
      <a href="frontend/browse.php?category=bags" class="cat-card">
        <span class="cat-icon">👜</span>
        <div class="cat-name">Bags & Accessories</div>
        <div class="cat-count">95 listings</div>
      </a>
      <a href="frontend/browse.php?category=denim" class="cat-card">
        <span class="cat-icon">👖</span>
        <div class="cat-name">Denim & Bottoms</div>
        <div class="cat-count">278 listings</div>
      </a>
    </div>
  </section>

  <!-- HOW IT WORKS -->
  <section class="landing-section" id="how">
    <div class="section-eyebrow">How it works</div>
    <h2 class="section-heading">Simple, safe, <em>South African</em></h2>
    <p class="section-sub">Built for local buyers and sellers — no confusing fees, no anonymous listings. Just real people and real clothes.</p>

    <div class="how-grid">
      <div class="how-steps">
        <div class="how-step">
          <div class="step-num">1</div>
          <div>
            <div class="step-title">Create your account</div>
            <div class="step-desc">Sign up as a buyer or seller. Every seller is reviewed and verified by our team before they can list — so you always know who you're buying from.</div>
          </div>
        </div>
        <div class="how-step">
          <div class="step-num">2</div>
          <div>
            <div class="step-title">Browse or list</div>
            <div class="step-desc">Search curated listings from verified South African sellers and filter by city, size, and price — or photograph and list your items in minutes.</div>
          </div>
        </div>
        <div class="how-step">
          <div class="step-num">3</div>
          <div>
            <div class="step-title">Buy or sell with confidence</div>
            <div class="step-desc">Secure checkout, local delivery options, and direct messaging with your seller or buyer. Fashion has always been about people.</div>
          </div>
        </div>
      </div>

      <div class="role-cards">
        <div class="role-card">
          <span class="role-tag seller">Selling</span>
          <div class="role-title">Turn your wardrobe into income</div>
          <div class="role-desc">List your preloved pieces, set your price, and connect directly with buyers across South Africa. Your profile, your archive, your rules.</div>
          <a href="frontend/register.php?role=seller" class="role-link">Become a seller <i class="fas fa-arrow-right" style="font-size:.7rem"></i></a>
        </div>
        <div class="role-card">
          <span class="role-tag buyer">Buying</span>
          <div class="role-title">One-of-a-kind pieces, real prices</div>
          <div class="role-desc">No algorithm, no fast fashion. Discover pieces with character from verified sellers in your city and beyond — all under one roof.</div>
          <a href="frontend/browse.php" class="role-link">Start browsing <i class="fas fa-arrow-right" style="font-size:.7rem"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- FRESH LISTINGS -->
  <section class="landing-section alt" id="listings">
    <div class="listings-header">
      <div>
        <div class="section-eyebrow">Just listed</div>
        <h2 class="section-heading" style="margin-bottom:0">Fresh off the <em>rack</em></h2>
      </div>
      <a href="frontend/browse.php" class="text-primary fw-600 fs-sm" style="display:flex;align-items:center;gap:6px">
        View all listings <i class="fas fa-arrow-right" style="font-size:.7rem"></i>
      </a>
    </div>

    <div class="listings-grid">
      <!-- These are demo cards — in production, replace with a DB query -->
      <a href="frontend/browse.php" class="product-card">
        <div class="product-card-img">
          <div style="width:100%;height:100%;background:linear-gradient(155deg,#C9A898 0%,#A8705C 60%,#7A4A38 100%)"></div>
          <span class="product-card-badge editorial">Vintage</span>
          <span class="product-card-heart"><i class="fas fa-heart"></i></span>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Ralph Lauren Polo Shirt — Navy</div>
          <div class="product-card-meta">Size M · Cape Town</div>
          <div class="product-card-price">R 180</div>
        </div>
      </a>
      <a href="frontend/browse.php" class="product-card">
        <div class="product-card-img">
          <div style="width:100%;height:100%;background:linear-gradient(155deg,#A8B89C 0%,#7A9870 60%,#527050 100%)"></div>
          <span class="product-card-badge editorial">Y2K</span>
          <span class="product-card-heart"><i class="fas fa-heart"></i></span>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Flared Corduroy Trouser — Teal</div>
          <div class="product-card-meta">Size S · Johannesburg</div>
          <div class="product-card-price">R 240</div>
        </div>
      </a>
      <a href="frontend/browse.php" class="product-card">
        <div class="product-card-img">
          <div style="width:100%;height:100%;background:linear-gradient(155deg,#C0B4A0 0%,#9A8068 60%,#705848 100%)"></div>
          <span class="product-card-badge rare">Rare find</span>
          <span class="product-card-heart"><i class="fas fa-heart"></i></span>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Champion Reverse Weave Hoodie</div>
          <div class="product-card-meta">Size L · Pretoria</div>
          <div class="product-card-price">R 320</div>
        </div>
      </a>
      <a href="frontend/browse.php" class="product-card">
        <div class="product-card-img">
          <div style="width:100%;height:100%;background:linear-gradient(155deg,#B8AEC0 0%,#8878A0 60%,#604870 100%)"></div>
          <span class="product-card-badge editorial">Archive</span>
          <span class="product-card-heart"><i class="fas fa-heart"></i></span>
        </div>
        <div class="product-card-info">
          <div class="product-card-title">Levi's 501 — Washed Black</div>
          <div class="product-card-meta">Size 32 · Durban</div>
          <div class="product-card-price">R 290</div>
        </div>
      </a>
    </div>
  </section>

  <!-- CTA STRIP -->
  <div style="padding:72px 0 0">
    <div class="cta-strip">
      <div>
        <h2 class="cta-title">Your next favourite piece is already out there. Go find it.</h2>
        <p class="cta-sub">Free to join, free to browse. Thousands of South Africans are already buying and selling on Pastimes.</p>
      </div>
      <div class="cta-actions">
        <a href="frontend/register.php" class="btn-cta-white">Create free account</a>
        <a href="frontend/register.php?role=seller" class="btn-cta-ghost">I want to sell</a>
        <span class="cta-note">No credit card required</span>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="landing-footer" id="about">
    <div class="footer-grid">
      <div>
        <div class="footer-logo">Pastimes</div>
        <p class="footer-tagline">South Africa's home for preloved fashion. Buy and sell verified clothing directly between real people.</p>
      </div>
      <div class="footer-col">
        <h4>Marketplace</h4>
        <ul>
          <li><a href="frontend/browse.php">Browse all</a></li>
          <li><a href="frontend/browse.php?sort=new">New listings</a></li>
          <li><a href="frontend/register.php?role=seller">Sell an item</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="#about">About Pastimes</a></li>
          <li><a href="#how">How it works</a></li>
          <li><a href="frontend/contact.php">Contact</a></li>
          <li><a href="admin/admin-login.php">Admin</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Legal</h4>
        <ul>
          <li><a href="#">Terms of use</a></li>
          <li><a href="#">Privacy policy</a></li>
          <li><a href="#">Seller policy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span class="footer-copy">© <?= date('Y') ?> Pastimes · South Africa</span>
      <div class="footer-legal">
        <a href="#">Terms</a>
        <a href="#">Privacy</a>
      </div>
    </div>
  </footer>

  <script>
    // Pause ticker on hover
    document.querySelector('.ticker-track').addEventListener('mouseenter', function () {
      this.style.animationPlayState = 'paused';
    });
    document.querySelector('.ticker-track').addEventListener('mouseleave', function () {
      this.style.animationPlayState = 'running';
    });
  </script>

</body>
</html>
