// Global variables
let products = [];
let categories = [];
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Load data from JSON file
async function loadData() {
    try {
        const response = await fetch('data/products.json');
        const data = await response.json();
        products = data.products;
        categories = data.categories;
        renderProducts();
        renderCategories();
    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// Render products
function renderProducts(filteredProducts = null) {
    const productsGrid = document.querySelector('.products-grid');
    if (!productsGrid) return;

    const productsToShow = filteredProducts || products;
    productsGrid.innerHTML = productsToShow.map(product => `
        <div class="product-card">
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p class="price">${formatPrice(product.price)}</p>
            <button onclick="addToCart(${product.id})" class="btn btn-primary">
                Thêm vào giỏ hàng
            </button>
        </div>
    `).join('');
}

// Render categories
function renderCategories() {
    const categoriesList = document.querySelector('.categories');
    if (!categoriesList) return;

    categoriesList.innerHTML = categories.map(category => `
        <li>
            <a href="#" onclick="filterByCategory(${category.id})">
                ${category.name}
            </a>
        </li>
    `).join('');
}

// Add to cart
function addToCart(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    const existingItem = cart.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: 1
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showMessage('Đã thêm sản phẩm vào giỏ hàng');
}

// Update cart count
function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (!cartCount) return;

    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
}

// Filter products by category
function filterByCategory(categoryId) {
    const filteredProducts = products.filter(product => product.category_id === categoryId);
    renderProducts(filteredProducts);
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Show message
function showMessage(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 3000);
}

// Search products
function searchProducts(query) {
    const filteredProducts = products.filter(product => 
        product.name.toLowerCase().includes(query.toLowerCase())
    );
    renderProducts(filteredProducts);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadData();
    updateCartCount();

    // Add search functionality
    const searchInput = document.querySelector('.search input');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchProducts(e.target.value);
        });
    }
}); 