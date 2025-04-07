// Hiển thị giỏ hàng
function renderCart() {
    const cartItems = document.querySelector('.cart_items');
    const subtotalEl = document.querySelector('.subtotal');
    const shippingEl = document.querySelector('.shipping');
    const totalEl = document.querySelector('.total_amount');
    
    // Lấy giỏ hàng từ localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty_cart">Giỏ hàng trống</p>';
        subtotalEl.textContent = '0đ';
        shippingEl.textContent = '0đ';
        totalEl.textContent = '0đ';
        return;
    }
    
    let subtotal = 0;
    let html = '';
    
    // Hiển thị từng sản phẩm trong giỏ hàng
    cart.forEach(item => {
        const product = products.find(p => p.id === item.id);
        if (product) {
            const itemTotal = product.price * item.quantity;
            subtotal += itemTotal;
            
            html += `
                <div class="cart_item" data-id="${product.id}">
                    <div class="item_image">
                        <img src="${product.image}" alt="${product.name}">
                    </div>
                    <div class="item_info">
                        <h3>${product.name}</h3>
                        <p class="price">${formatPrice(product.price)}</p>
                    </div>
                    <div class="item_quantity">
                        <button onclick="updateQuantity(${product.id}, ${item.quantity - 1})">-</button>
                        <input type="number" value="${item.quantity}" min="1" 
                               onchange="updateQuantity(${product.id}, this.value)">
                        <button onclick="updateQuantity(${product.id}, ${item.quantity + 1})">+</button>
                    </div>
                    <div class="item_total">
                        ${formatPrice(itemTotal)}
                    </div>
                    <div class="item_actions">
                        <button onclick="removeFromCart(${product.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }
    });
    
    cartItems.innerHTML = html;
    
    // Tính phí vận chuyển (ví dụ: 10% tổng tiền)
    const shipping = subtotal * 0.1;
    const total = subtotal + shipping;
    
    // Hiển thị tổng tiền
    subtotalEl.textContent = formatPrice(subtotal);
    shippingEl.textContent = formatPrice(shipping);
    totalEl.textContent = formatPrice(total);
}

// Cập nhật số lượng sản phẩm
function updateQuantity(productId, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (newQuantity < 1) return;
    
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const index = cart.findIndex(item => item.id === productId);
    
    if (index !== -1) {
        cart[index].quantity = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
        updateCartCount();
    }
}

// Xóa sản phẩm khỏi giỏ hàng
function removeFromCart(productId) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const newCart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(newCart));
    renderCart();
    updateCartCount();
}

// Thanh toán
function checkout() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Giỏ hàng trống!');
        return;
    }
    
    // Trong thực tế, bạn sẽ chuyển hướng đến trang thanh toán
    alert('Chức năng thanh toán đang được phát triển!');
}

// Format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Khởi tạo trang
document.addEventListener('DOMContentLoaded', () => {
    loadData().then(() => {
        renderCart();
        updateCartCount();
    });
}); 