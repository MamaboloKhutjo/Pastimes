 function setRole(role, el) {
      document.querySelectorAll('.auth-role-btn').forEach(b => b.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('roleInput').value = role;
      document.getElementById('sellerFields').style.display = role === 'seller' ? 'block' : 'none';
    }

    function setFilter(filter, el) {
      document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      el.classList.add('active');
      // PHP: fetch products by filter
      // fetch('php/?filter=' + filter).then(...)
    }
 
    function toggleHeart(e, el) {
      e.stopPropagation();
      el.classList.toggle('liked');
      const icon = el.querySelector('i');
      icon.className = el.classList.contains('liked') ? 'fas fa-heart' : 'far fa-heart';
      // PHP: toggle wishlist
      // fetch('php/?product_id=...&action=toggle')
    }
    function setImg(thumb, src) {
      document.querySelectorAll('.product-thumb').forEach(t => t.classList.remove('active'));
      thumb.classList.add('active');
      document.getElementById('mainImg').src = src;
    }
 
    function addToCart() {
      // PHP: POST to backend
      // fetch('php/', { method:'POST', body: JSON.stringify({product_id: PRODUCT_ID}) })
      alert('Added to cart!');
    }
 
    function followSeller() {
      // PHP: POST to backend
    }
    function selectDelivery(el, method, price) {
      document.querySelectorAll('.delivery-option').forEach(d => d.classList.remove('selected'));
      el.classList.add('selected');
      document.getElementById('deliveryMethodInput').value = method;
      document.getElementById('deliveryFee').textContent = 'R ' + price;
      updateTotal();
    }
 
    function updateQty(itemId, delta) {
      const el = document.getElementById('qty-' + itemId);
      let q = parseInt(el.textContent) + delta;
      if (q < 1) q = 1;
      el.textContent = q;
      // PHP: update cart quantity
      updateTotal();
    }
 
    function removeItem(itemId) {
      // PHP: remove from cart
      document.querySelectorAll('.cart-item')[itemId - 1].remove();
    }
 
    function applyPromo() {
      document.getElementById('promoCodeInput').value = document.getElementById('promoInput').value;
      // PHP: validate promo code and update totals
    }
 
    function addBundle() {
      // PHP: add bundle item to cart
    }
 
    function updateTotal() {
      // Recalculate client-side; real validation server-side
    }
    function previewImage(event, slotId) {
      const slot = document.getElementById(slotId);
      const file = event.target.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const existing = slot.querySelector('img');
        if (existing) existing.remove();
        const img = document.createElement('img');
        img.src = e.target.result;
        slot.insertBefore(img, slot.querySelector('input'));
        slot.querySelector('i') && (slot.querySelector('i').style.display = 'none');
        slot.querySelector('.photo-slot-label') && (slot.querySelector('.photo-slot-label').style.display = 'none');
      };
      reader.readAsDataURL(file);
    }
 
    function setCategory(el) {
      document.querySelectorAll('.category-chip').forEach(c => c.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('categoryInput').value = el.dataset.val;
    }
 
    function saveDraft() {
      // PHP: auto-save form data as draft
      document.getElementById('draftBtn').textContent = 'Saved ✓';
      setTimeout(() => document.getElementById('draftBtn').textContent = 'Draft', 2000);
    }
    // Auto-save draft every 30s
    setInterval(saveDraft, 30000);

    function sendMsg() {
      const input = document.getElementById('msgInput');
      const text = input.value.trim();
      if (!text) return;
 
      // PHP: POST message to backend
      // fetch('php/', { method:'POST', body: JSON.stringify({conversation_id: ID, message: text}) })
 
      // Optimistic UI update
      const chatBody = document.getElementById('chatBody');
      const bubble = document.createElement('div');
      bubble.className = 'bubble-row sent';
      bubble.innerHTML = `<div class="bubble">${text}</div><span class="bubble-time">${new Date().toLocaleTimeString('en-ZA',{hour:'2-digit',minute:'2-digit'})}</span>`;
      chatBody.appendChild(bubble);
      input.value = '';
      chatBody.scrollTop = chatBody.scrollHeight;
    }
 
    function attachFile() {
      document.getElementById('fileAttach').click();
    }
 
    function previewAttachment(event) {
      // PHP: upload image and send in message
    }
 
    function respondOffer(action) {
      // PHP: POST offer response
      // fetch('php/', { method:'POST', body: JSON.stringify({action: 'offer_response', response: action}) })
    }
 
    // Scroll to bottom on load
    const chatBody = document.getElementById('chatBody');
    if (chatBody) {
      chatBody.scrollTop = chatBody.scrollHeight;
    }
 
    // PHP: polling or WebSocket for new messages
    // setInterval(() => fetchNewMessages(), 5000);

    function setTab(el, tab) {
      document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('storefrontGrid').style.display = tab === 'storefront' ? 'grid' : 'none';
      document.getElementById('soldGrid').style.display = tab === 'sold' ? 'block' : 'none';
      document.getElementById('reviewsGrid').style.display = tab === 'reviews' ? 'block' : 'none';
      document.getElementById('aboutGrid').style.display = tab === 'about' ? 'block' : 'none';
      // PHP: load tab content if not already loaded
    }
 
    function toggleFollow(btn) {
      const following = btn.textContent.trim() === 'Following';
      btn.textContent = following ? 'Follow' : 'Following';
      btn.className = following ? 'btn btn-primary' : 'btn btn-secondary';
      // PHP: toggle follow
    }

    function approveReg(id) {
      if (!confirm('Approve this registration?')) return;
      // PHP: POST to backend
      // fetch('php/', { method:'POST', body: JSON.stringify({action:'approve', id}) })
      //   .then(() => document.querySelector(`.reg-card:nth-child(${id})`).remove())
      alert('Registration approved. PHP handler needed.');
    }
 
    function rejectReg(id) {
      if (!confirm('Reject this registration?')) return;
      // PHP: POST to backend
      alert('Registration rejected. PHP handler needed.');
    }
 
    function openBroadcast() {
      document.getElementById('broadcastModal').style.display = 'flex';
    }
 
    function closeBroadcast() {
      document.getElementById('broadcastModal').style.display = 'none';
    }

    const searchSamples = [
      { title: '1990s Leather Jacket', meta: 'Vintage · Size M', price: 'R 2 850' },
      { title: 'Silk Wrap Skirt', meta: 'New with tags · Size S', price: 'R 1 200' },
      { title: 'Cashmere Crewneck', meta: 'Luxury · Size L', price: 'R 3 400' },
      { title: 'Archive Silk Scarf', meta: 'Accessories · Vintage · Durban', price: 'R 950' },
      { title: 'Japanese Denim Jacket', meta: 'Rare Find · Size L', price: 'R 5 500' }
    ];

    function performSearch() {
      const input = document.getElementById('searchInput');
      const resultsEl = document.getElementById('searchResults');
      if (!input || !resultsEl) return;
      const query = input.value.trim().toLowerCase();
      if (!query) {
        resultsEl.style.display = 'none';
        resultsEl.innerHTML = '';
        return;
      }

      const matches = searchSamples.filter(item =>
        item.title.toLowerCase().includes(query) || item.meta.toLowerCase().includes(query)
      );

      resultsEl.innerHTML = matches.length
        ? matches.map(item => `
          <div class="product-card" onclick="location.href='product.html'">
            <div class="product-card-img">
              <img src="./assets/images/generic_clothing.jpg" alt="${item.title}">
              <div class="product-card-heart" onclick="event.stopPropagation(); toggleHeart(event,this)"><i class="far fa-heart"></i></div>
            </div>
            <div class="product-card-info">
              <div class="product-card-title">${item.title}</div>
              <div class="product-card-meta">${item.meta}</div>
              <div class="product-card-price">${item.price}</div>
            </div>
          </div>
        `).join('')
        : '<div style="width:100%;padding:24px;border:1.5px solid var(--clr-border);border-radius:var(--radius-md);background:var(--clr-surface);color:var(--clr-muted);text-align:center">No results found.</div>';

      resultsEl.style.display = 'grid';
    }

    //
    function openBroadcast() {
      document.getElementById('broadcastModal').style.display = 'flex';
    }
    function closeBroadcast() {
      document.getElementById('broadcastModal').style.display = 'none';
    }
