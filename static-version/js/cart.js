// Khởi tạo giỏ hàng từ localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Hiển thị giỏ hàng
function renderCart() {
    const cartContent = document.querySelector('.cart-content');
    const emptyCart = document.querySelector('.empty-cart');
    const cartSummary = document.querySelector('.cart-summary');
    
    if (cart.length === 0) {
        cartContent.style.display = 'none';
        emptyCart.style.display = 'block';
        cartSummary.style.display = 'none';
        return;
    }
    
    cartContent.style.display = 'grid';
    emptyCart.style.display = 'none';
    cartSummary.style.display = 'block';
    
    // Hiển thị danh sách sản phẩm
    const cartItems = document.querySelector('.cart-items');
    cartItems.innerHTML = cart.map(item => `
        <div class="cart-item" data-id="${item.id}">
            <div class="item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="item-info">
                <h3>${item.name}</h3>
                <p class="item-price">${formatPrice(item.price)}</p>
            </div>
            <div class="item-quantity">
                <button class="quantity-btn minus" onclick="updateQuantity(${item.id}, -1)">-</button>
                <input type="number" value="${item.quantity}" min="1" onchange="updateQuantityInput(${item.id}, this.value)">
                <button class="quantity-btn plus" onclick="updateQuantity(${item.id}, 1)">+</button>
            </div>
            <div class="item-total">
                ${formatPrice(item.price * item.quantity)}
            </div>
            <button class="remove-btn" onclick="removeItem(${item.id})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `).join('');
    
    // Cập nhật tổng tiền
    updateCartTotal();
}

// Cập nhật số lượng sản phẩm
function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = Math.max(1, item.quantity + change);
        saveCart();
        renderCart();
    }
}

// Cập nhật số lượng sản phẩm từ input
function updateQuantityInput(productId, value) {
    const quantity = parseInt(value);
    if (quantity > 0) {
        const item = cart.find(item => item.id === productId);
        if (item) {
            item.quantity = quantity;
            saveCart();
            renderCart();
        }
    }
}

// Xóa sản phẩm khỏi giỏ hàng
function removeItem(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    renderCart();
    updateCartCount();
}

// Cập nhật tổng tiền giỏ hàng
function updateCartTotal() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const shipping = subtotal > 0 ? 50000 : 0; // Phí ship 50,000đ
    const total = subtotal + shipping;
    
    document.querySelector('.subtotal').textContent = formatPrice(subtotal);
    document.querySelector('.shipping').textContent = formatPrice(shipping);
    document.querySelector('.total').textContent = formatPrice(total);
}

// Lưu giỏ hàng vào localStorage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Cập nhật số lượng sản phẩm trên icon giỏ hàng
function updateCartCount() {
    const count = cart.reduce((total, item) => total + item.quantity, 0);
    document.querySelector('.cart-count').textContent = count;
}

// Format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Xử lý nút thanh toán
document.querySelector('.checkout-btn').addEventListener('click', () => {
    if (cart.length === 0) {
        showError('Giỏ hàng của bạn đang trống');
        return;
    }
    
    // Kiểm tra đăng nhập
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    if (!isLoggedIn) {
        if (confirm('Bạn cần đăng nhập để thanh toán. Đến trang đăng nhập?')) {
            window.location.href = 'account.html';
        }
        return;
    }
    
    // Trong thực tế, đây sẽ là API call để tạo đơn hàng
    alert('Đặt hàng thành công! Cảm ơn bạn đã mua hàng.');
    cart = [];
    saveCart();
    renderCart();
    updateCartCount();
});

// Hiển thị thông báo lỗi
function showError(message) {
    alert(message);
}

// Khởi tạo trang
document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    updateCartCount();
}); 