// ============================================
// NAVBAR TOGGLE
// ============================================
const navbarNav = document.querySelector(".navbar-nav");
const hamburger = document.querySelector("#hamburger-menu");

// ketika hamburger-menu di klik
document.querySelector("#hamburger-menu").onclick = (e) => {
  navbarNav.classList.toggle("active");
  e.stopPropagation();
};

// Klik di luar sidebar untuk menghilangkan nav
document.addEventListener("click", function (e) {
  if (!hamburger.contains(e.target) && !navbarNav.contains(e.target)) {
    navbarNav.classList.remove("active");
  }
});

// ============================================
// CART FUNCTIONALITY
// ============================================
let cart = [];

// DOM Elements
const cartSidebar = document.getElementById("cart-sidebar");
const cartOverlay = document.getElementById("cart-overlay");
const closeCartBtn = document.getElementById("close-cart");
const cartItemsContainer = document.getElementById("cart-items");
const cartTotalPrice = document.getElementById("cart-total-price");
const checkoutBtn = document.getElementById("checkout-btn");
const clearCartBtn = document.getElementById("clear-cart-btn");
const shoppingCartIcon = document.getElementById("shopping-cart");

// Open Cart
shoppingCartIcon.addEventListener("click", (e) => {
  e.preventDefault();
  openCart();
});

// Close Cart
closeCartBtn.addEventListener("click", closeCart);
cartOverlay.addEventListener("click", closeCart);

// Open Cart Function
function openCart() {
  cartSidebar.classList.add("active");
  cartOverlay.classList.add("active");
  document.body.style.overflow = "hidden";
}

// Close Cart Function
function closeCart() {
  cartSidebar.classList.remove("active");
  cartOverlay.classList.remove("active");
  document.body.style.overflow = "auto";
}

// Add to Cart Function
function addToCart(id, name, price, size) {
  // Konversi id ke number
  id = parseInt(id);
  
  // Cek apakah item sudah ada di cart
  const existingItemIndex = cart.findIndex(item => 
    item.id === id && item.size === size
  );
  
  if (existingItemIndex !== -1) {
    // Jika sudah ada, tambah quantity
    cart[existingItemIndex].qty += 1;
    cart[existingItemIndex].subtotal = cart[existingItemIndex].qty * cart[existingItemIndex].price;
  } else {
    // Jika belum ada, tambah item baru
    const newItem = {
      id: id,
      name: name,
      price: parseInt(price),
      size: size,
      qty: 1,
      subtotal: parseInt(price)
    };
    cart.push(newItem);
  }
  
  renderCart();
  openCart();
  showNotification(`${name} ditambahkan ke keranjang!`);
}

// Remove Item from Cart
function removeItem(id, size) {
  id = parseInt(id);
  cart = cart.filter(item => !(item.id === id && item.size === size));
  renderCart();
  
  if (cart.length === 0) {
    showNotification("Keranjang dikosongkan");
  }
}

// Update Quantity
function updateQty(id, size, newQty) {
  id = parseInt(id);
  
  if (newQty < 1) {
    removeItem(id, size);
    return;
  }
  
  const itemIndex = cart.findIndex(item => 
    item.id === id && item.size === size
  );
  
  if (itemIndex !== -1) {
    cart[itemIndex].qty = newQty;
    cart[itemIndex].subtotal = cart[itemIndex].qty * cart[itemIndex].price;
    renderCart();
  }
}

// Calculate Total
function calculateTotal() {
  return cart.reduce((total, item) => total + item.subtotal, 0);
}

// Render Cart Items
function renderCart() {
  if (cart.length === 0) {
    cartItemsContainer.innerHTML = `
      <div class="empty-cart">
        <i data-feather="shopping-bag"></i>
        <p>Keranjang masih kosong</p>
      </div>
    `;
    cartTotalPrice.textContent = "Rp0";
    feather.replace();
    return;
  }
  
  let cartHTML = '';
  
  cart.forEach(item => {
    cartHTML += `
      <div class="cart-item" data-id="${item.id}" data-size="${item.size}">
        <img src="img/donjt2.jpg" alt="${item.name}" class="cart-item-image">
        <div class="cart-item-details">
          <div class="cart-item-name">${item.name}</div>
          <div class="cart-item-size">Size: ${item.size}</div>
          <div class="cart-item-price">Rp${item.price.toLocaleString('id-ID')}</div>
          <div class="cart-item-controls">
            <button class="qty-btn minus" data-id="${item.id}" data-size="${item.size}">-</button>
            <span class="cart-item-qty">${item.qty}</span>
            <button class="qty-btn plus" data-id="${item.id}" data-size="${item.size}">+</button>
            <button class="remove-item" data-id="${item.id}" data-size="${item.size}">
              <i data-feather="trash"></i>
            </button>
          </div>
          <div style="margin-top: 0.5rem; font-weight: 600;">
            Subtotal: <span style="color: var(--primary);">Rp${item.subtotal.toLocaleString('id-ID')}</span>
          </div>
        </div>
      </div>
    `;
  });
  
  cartItemsContainer.innerHTML = cartHTML;
  cartTotalPrice.textContent = `Rp${calculateTotal().toLocaleString('id-ID')}`;
  
  // Re-initialize feather icons
  feather.replace();
  
  // Attach event listeners to the new buttons
  attachCartItemListeners();
}

// Attach event listeners to cart items
function attachCartItemListeners() {
  // Quantity minus buttons
  document.querySelectorAll('.qty-btn.minus').forEach(button => {
    button.addEventListener('click', function() {
      const id = parseInt(this.getAttribute('data-id'));
      const size = this.getAttribute('data-size');
      const item = cart.find(item => item.id === id && item.size === size);
      if (item) {
        updateQty(id, size, item.qty - 1);
      }
    });
  });
  
  // Quantity plus buttons
  document.querySelectorAll('.qty-btn.plus').forEach(button => {
    button.addEventListener('click', function() {
      const id = parseInt(this.getAttribute('data-id'));
      const size = this.getAttribute('data-size');
      const item = cart.find(item => item.id === id && item.size === size);
      if (item) {
        updateQty(id, size, item.qty + 1);
      }
    });
  });
  
  // Remove buttons
  document.querySelectorAll('.remove-item').forEach(button => {
    button.addEventListener('click', function() {
      const id = parseInt(this.getAttribute('data-id'));
      const size = this.getAttribute('data-size');
      removeItem(id, size);
    });
  });
}

// Save Transaction dengan fallback
async function saveTransaction() {
  if (cart.length === 0) {
    alert("Keranjang kosong! Tambahkan produk terlebih dahulu.");
    return;
  }
  
  const transactionData = {
    items: cart,
    total: calculateTotal(),
    created_at: new Date().toISOString()
  };
  
  // Tampilkan loading
  checkoutBtn.innerHTML = '<i data-feather="loader"></i> Menyimpan...';
  checkoutBtn.disabled = true;
  feather.replace();
  
  try {
    console.log('Mengirim data:', transactionData);
    const response = await fetch('http://localhost/kantin-bahagia/save_transaction.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(transactionData)
    });
    
    console.log('Response status:', response.status);
    
    // Coba parse JSON response
    let result;
    try {
      const text = await response.text();
      console.log('Response text:', text);
      result = JSON.parse(text);
    } catch (parseError) {
      console.error('Parse error:', parseError);
      throw new Error('Response bukan JSON valid');
    }
    
    console.log('Response JSON:', result);
    
    if (result.success) {
      // Reset cart
      cart = [];
      renderCart();
      closeCart();
      showNotification("Transaksi berhasil disimpan! Mode: " + (result.mode || 'database'));
      
      // Tampilkan detail di console untuk debugging
      console.log('Transaction saved successfully:', result);
      
    } else {
      alert("Gagal menyimpan transaksi: " + (result.error || "Unknown error"));
      console.error('Server error:', result);
    }
    
  } catch (error) {
    console.error('Fetch Error:', error);
    
    // Fallback: Simpan ke localStorage
    const transactions = JSON.parse(localStorage.getItem('kantin_transactions') || '[]');
    const newTransaction = {
      ...transactionData,
      id: Date.now(),
      saved_via: 'localStorage',
      local_timestamp: new Date().toLocaleString('id-ID')
    };
    
    transactions.push(newTransaction);
    localStorage.setItem('kantin_transactions', JSON.stringify(transactions));
    
    // Reset cart
    cart = [];
    renderCart();
    closeCart();
    
    showNotification("Transaksi disimpan secara lokal (Browser Storage)");
    console.log('Saved to localStorage:', newTransaction);
    
  } finally {
    // Reset button state
    checkoutBtn.innerHTML = '<i data-feather="credit-card"></i> Simpan Transaksi';
    checkoutBtn.disabled = false;
    feather.replace();
  }
}

// Clear Cart
function clearCart() {
  if (cart.length === 0) return;
  
  if (confirm("Apakah Anda yakin ingin mengosongkan keranjang?")) {
    cart = [];
    renderCart();
    showNotification("Keranjang dikosongkan");
  }
}

// Notification
function showNotification(message) {
  // Hapus notifikasi sebelumnya jika ada
  const oldNotifications = document.querySelectorAll('.notification');
  oldNotifications.forEach(notif => notif.remove());
  
  // Buat elemen notifikasi
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;
  notification.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: var(--primary);
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    z-index: 10000;
    animation: slideIn 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  `;
  
  document.body.appendChild(notification);
  
  // Hapus setelah 3 detik
  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => {
      if (notification.parentNode) {
        document.body.removeChild(notification);
      }
    }, 300);
  }, 3000);
}

// Add CSS for notification animation
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
  }
  @keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
  }
`;
document.head.appendChild(style);

// Event Listeners
checkoutBtn.addEventListener('click', saveTransaction);
clearCartBtn.addEventListener('click', clearCart);

// Initialize Add to Cart buttons
document.addEventListener('DOMContentLoaded', function() {
  // Set up add to cart buttons
  const addToCartButtons = document.querySelectorAll('.add-to-cart');
  
  addToCartButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      const price = this.getAttribute('data-price');
      const size = this.getAttribute('data-size');
      
      addToCart(id, name, price, size);
    });
  });
  
  // Initialize feather icons
  feather.replace();
  
  // Render cart initially
  renderCart();

  // ============================================
// LOGIN FUNCTIONALITY
// ============================================

// DOM Elements
const loginBtn = document.getElementById('login-btn');
const loginModal = document.getElementById('login-modal');
const closeLoginBtn = document.querySelectorAll('.close-login');
const loginForm = document.getElementById('login-form');
const loginMessage = document.getElementById('login-message');

// Open Login Modal
if (loginBtn) {
  loginBtn.addEventListener('click', function(e) {
    e.preventDefault();
    openLoginModal();
  });
}

// Close Login Modal
closeLoginBtn.forEach(btn => {
  btn.addEventListener('click', closeLoginModal);
});

// Close modal when clicking outside
loginModal.addEventListener('click', function(e) {
  if (e.target === loginModal) {
    closeLoginModal();
  }
});

// Open Login Modal Function
function openLoginModal() {
  loginModal.classList.add('active');
  document.body.style.overflow = 'hidden';
  // Reset form
  loginForm.reset();
  loginMessage.style.display = 'none';
  loginMessage.className = 'login-message';
}

// Close Login Modal Function
function closeLoginModal() {
  loginModal.classList.remove('active');
  document.body.style.overflow = 'auto';
}

// Handle Login Form Submission
if (loginForm) {
  loginForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('.login-submit-btn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading
    submitBtn.innerHTML = '<i data-feather="loader"></i> Memproses...';
    submitBtn.disabled = true;
    feather.replace();
    
    try {
      const response = await fetch('login_process.php', {
        method: 'POST',
        body: formData
      });
      
      const result = await response.json();
      
      if (result.success) {
        // Login successful
        loginMessage.textContent = result.message;
        loginMessage.className = 'login-message success';
        loginMessage.style.display = 'block';
        
        // Update UI
        updateAdminUI(true);
        
        // Close modal after 2 seconds
        setTimeout(() => {
          closeLoginModal();
          showNotification('Login admin berhasil!');
        }, 2000);
        
      } else {
        // Login failed
        loginMessage.textContent = result.error;
        loginMessage.className = 'login-message error';
        loginMessage.style.display = 'block';
        
        // Shake animation for error
        loginModalContent = document.querySelector('.login-modal-content');
        loginModalContent.style.animation = 'shake 0.5s';
        setTimeout(() => {
          loginModalContent.style.animation = '';
        }, 500);
      }
      
    } catch (error) {
      console.error('Login error:', error);
      loginMessage.textContent = 'Koneksi ke server gagal';
      loginMessage.className = 'login-message error';
      loginMessage.style.display = 'block';
      
    } finally {
      // Reset button
      submitBtn.innerHTML = originalText;
      submitBtn.disabled = false;
      feather.replace();
    }
  });
}

// Update Admin UI
function updateAdminUI(isLoggedIn) {
  if (isLoggedIn) {
    // Change login button to admin badge
    loginBtn.style.display = 'none';
    
    // Create admin badge if not exists
    let adminBadge = document.querySelector('.admin-badge');
    if (!adminBadge) {
      adminBadge = document.createElement('div');
      adminBadge.className = 'admin-badge active';
      adminBadge.innerHTML = `
        <i data-feather="user-check"></i>
        <span>Admin</span>
        <button class="logout-btn" id="logout-btn">
          <i data-feather="log-out"></i>
        </button>
      `;
      
      // Insert after login button
      loginBtn.parentNode.insertBefore(adminBadge, loginBtn.nextSibling);
      
      // Add logout event
      document.getElementById('logout-btn').addEventListener('click', logoutAdmin);
      feather.replace();
    } else {
      adminBadge.classList.add('active');
    }
    
    // Save login state in localStorage (for demo)
    localStorage.setItem('admin_logged_in', 'true');
    
  } else {
    // Show login button, hide badge
    loginBtn.style.display = 'block';
    
    const adminBadge = document.querySelector('.admin-badge');
    if (adminBadge) {
      adminBadge.classList.remove('active');
    }
    
    // Remove login state
    localStorage.removeItem('admin_logged_in');
  }
}

// Logout Function
function logoutAdmin() {
  // Send logout request
  fetch('logout.php')
    .then(response => response.json())
    .then(result => {
      if (result.success) {
        updateAdminUI(false);
        showNotification('Anda telah logout');
      }
    })
    .catch(error => {
      console.error('Logout error:', error);
      // Fallback: just update UI
      updateAdminUI(false);
      showNotification('Anda telah logout');
    });
}

// Check login status on page load
document.addEventListener('DOMContentLoaded', function() {
  // Check localStorage (for demo)
  const isLoggedIn = localStorage.getItem('admin_logged_in') === 'true';
  if (isLoggedIn) {
    updateAdminUI(true);
  }
  
  // Add shake animation
  const style = document.createElement('style');
  style.textContent = `
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
  `;
  document.head.appendChild(style);
});
});