<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Pastimes — Messages</title>
  <link rel="stylesheet" href="../frontend/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="rainbow-bar"></div>
  <div class="app-shell">
 
    <!-- Messages top bar -->
    <header class="messages-topbar">
      <button class="icon-btn" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
      <img src="#" alt="Elena V." style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid var(--clr-border)">
      <div class="messages-seller-info" style="flex:1;margin-left:10px">
        <!-- PHP: echo $conversation['other_user_name'] -->
        <strong>Elena V.</strong>
        <!-- PHP: if $other_user['seller_status'] === 'approved' -->
        <div class="messages-seller-badge">Approved Seller</div>
      </div>
      <button class="icon-btn"><i class="fas fa-ellipsis-v"></i></button>
    </header>
 
    <!-- Item context bar -->
    <!-- PHP: echo $conversation['product'] details -->
    <div class="messages-item-bar">
      <img src="#" alt="Trench">
      <div>
        <div class="messages-item-name">Vintage Oversized Trench</div>
        <div class="messages-item-meta">R 2 500 · Size M</div>
      </div>
      <div class="messages-item-actions">
        <!-- PHP: offer action -->
        <button class="btn btn-secondary btn-sm">Offer</button>
        <button class="btn btn-primary btn-sm" onclick="location.href='./cart.php'">Buy Now</button>
      </div>
    </div>
 
    <!-- Chat messages -->
    <!-- PHP: foreach $messages as $msg -->
    <div class="chat-body" id="chatBody">
 
      <div class="chat-date">Monday, 7 April 2025</div>
 
      <!-- Received -->
      <div class="bubble-row recv">
        <div class="bubble">
          Hi! Thanks for your interest. This trench is in pristine condition — worn only a handful of times for shoots here in Cape Town.
        </div>
        <span class="bubble-time">10:42 AM</span>
      </div>
 
      <!-- Sent -->
      <div class="bubble-row sent">
        <div class="bubble">
          Stunning! Could you share the pit-to-pit measurements? Want to make sure the oversized fit works for me.
        </div>
        <span class="bubble-time">10:45 AM ✓✓</span>
      </div>
 
      <!-- Received with images -->
      <div class="bubble-row recv">
        <div class="bubble">
          23 inches across — beautiful drape. Shipping via Courier Guy within 48 hours of payment.
          <div class="bubble-images">
            <img src="#" alt="detail">
            <img src="#" alt="detail">
          </div>
        </div>
        <span class="bubble-time">10:49 AM</span>
      </div>
 
      <!-- Offer bubble -->
      <div class="offer-bubble">
        <div class="offer-amount">Offer: R 2 200</div>
        <div class="offer-note">Buyer offered below asking price</div>
        <div class="offer-actions">
          <!-- PHP: action handled by backend -->
          <button class="btn btn-success btn-sm" onclick="respondOffer('accept')">Accept</button>
          <button class="btn btn-secondary btn-sm" onclick="respondOffer('decline')">Decline</button>
        </div>
      </div>
 
    </div>
    <!-- PHP: endforeach -->
 
    <!-- Spacer so content doesn't hide behind input -->
    <div style="height:80px"></div>
 
    <!-- Chat input -->
    <div class="chat-input-bar" style="position:fixed;bottom:72px;left:0;right:0">
      <span class="chat-attach-btn" onclick="attachFile()"><i class="fas fa-plus-circle"></i></span>
        <!-- PHP: action handled by backend -->
      <input class="chat-input" type="text" id="msgInput" placeholder="Type a message..." onkeydown="if(event.key==='Enter')sendMsg()">
      <span class="chat-send" onclick="sendMsg()"><i class="fas fa-paper-plane"></i></span>
      <input type="file" id="fileAttach" style="display:none" accept="image/*" onchange="previewAttachment(event)">
    </div>
 
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