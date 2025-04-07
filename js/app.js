// Lưu trữ dữ liệu
let products = [];
let categories = [];
let brands = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let users = JSON.parse(localStorage.getItem('users')) || [];

// Kiểm tra môi trường hoạt động
const isLocalhost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
const dataSource = isLocalhost ? '/export_data.php' : '/data/products.json';

// Load dữ liệu từ JSON hoặc API
async function loadData() {
    try {
        const response = await fetch(dataSource);
        const data = await response.json();
        products = data.products;
        categories = data.categories;
        brands = data.brands;
        renderProducts();
        renderCategories();
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Hiển thị sản phẩm
function renderProducts() {
    const productContainer = document.querySelector('.product_grid');
    if (!productContainer) return;

    productContainer.innerHTML = products.map(product => `
        <div class="product_item">
            <img src="./assets/img/${product.main_image}" alt="${product.product_name}">
            <h3>${product.product_name}</h3>
            <p class="price">${formatPrice(product.new_price)}đ</p>
            <button onclick="addToCart(${product.product_id})" class="btn">Thêm vào giỏ</button>
        </div>
    `).join('');
}

// Hiển thị danh mục
function renderCategories() {
    const categoryContainer = document.querySelector('.category_list');
    if (!categoryContainer) return;

    categoryContainer.innerHTML = categories.map(category => `
        <li>
            <a href="#" onclick="filterByCategory(${category.category_id})">
                ${category.category_name}
            </a>
        </li>
    `).join('');
}

// Thêm vào giỏ hàng
function addToCart(productId) {
    const product = products.find(p => p.product_id === productId);
    if (!product) return;

    const cartItem = cart.find(item => item.product_id === productId);
    if (cartItem) {
        cartItem.quantity += 1;
    } else {
        cart.push({
            product_id: product.product_id,
            product_name: product.product_name,
            price: product.new_price,
            quantity: 1,
            image: product.main_image
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showMessage('Đã thêm sản phẩm vào giỏ hàng');
}

// Cập nhật số lượng giỏ hàng
function updateCartCount() {
    const cartCount = document.querySelector('.cart_count');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

// Format giá tiền
function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Hiển thị thông báo
function showMessage(message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message';
    messageDiv.textContent = message;
    document.body.appendChild(messageDiv);
    setTimeout(() => messageDiv.remove(), 3000);
}

// Lọc sản phẩm theo danh mục
function filterByCategory(categoryId) {
    const filteredProducts = products.filter(p => p.category_id === categoryId);
    renderFilteredProducts(filteredProducts);
}

// Hiển thị sản phẩm đã lọc
function renderFilteredProducts(filteredProducts) {
    const productContainer = document.querySelector('.product_grid');
    if (!productContainer) return;

    productContainer.innerHTML = filteredProducts.map(product => `
        <div class="product_item">
            <img src="./assets/img/${product.main_image}" alt="${product.product_name}">
            <h3>${product.product_name}</h3>
            <p class="price">${formatPrice(product.new_price)}đ</p>
            <button onclick="addToCart(${product.product_id})" class="btn">Thêm vào giỏ</button>
        </div>
    `).join('');
}

// Tìm kiếm sản phẩm
function searchProducts() {
    const searchInput = document.getElementById('searchInput');
    const searchTerm = searchInput.value.toLowerCase();
    
    if (!searchTerm) {
        renderProducts();
        return;
    }
    
    const filteredProducts = products.filter(product => 
        product.product_name.toLowerCase().includes(searchTerm)
    );
    
    renderFilteredProducts(filteredProducts);
}

// Đăng ký tài khoản
function registerUser(username, password, email) {
    // Kiểm tra tài khoản đã tồn tại
    if (users.some(user => user.username === username)) {
        showMessage('Tên đăng nhập đã tồn tại');
        return false;
    }
    
    // Thêm người dùng mới
    const newUser = {
        id: users.length + 1,
        username,
        password, // Trong thực tế nên mã hóa mật khẩu
        email,
        created_at: new Date().toISOString()
    };
    
    users.push(newUser);
    localStorage.setItem('users', JSON.stringify(users));
    showMessage('Đăng ký thành công');
    return true;
}

// Đăng nhập
function loginUser(username, password) {
    const user = users.find(u => u.username === username && u.password === password);
    
    if (user) {
        localStorage.setItem('currentUser', JSON.stringify(user));
        showMessage('Đăng nhập thành công');
        return true;
    } else {
        showMessage('Tên đăng nhập hoặc mật khẩu không đúng');
        return false;
    }
}

// Đăng xuất
function logoutUser() {
    localStorage.removeItem('currentUser');
    showMessage('Đã đăng xuất');
}

// Kiểm tra đăng nhập
function isLoggedIn() {
    return localStorage.getItem('currentUser') !== null;
}

// Lấy thông tin người dùng hiện tại
function getCurrentUser() {
    return JSON.parse(localStorage.getItem('currentUser'));
}

// Khởi tạo khi trang load
document.addEventListener('DOMContentLoaded', () => {
    loadData();
    updateCartCount();
}); 