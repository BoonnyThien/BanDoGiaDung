document.addEventListener('DOMContentLoaded', function() {
    // Lấy ID sản phẩm từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (!productId) {
        window.location.href = 'index.html';
        return;
    }

    // Load thông tin sản phẩm
    loadProductDetails(productId);
    loadRelatedProducts(productId);

    // Xử lý sự kiện cho các nút tab
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked button and corresponding pane
            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Xử lý sự kiện cho thumbnail images
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.product-detail__main-image img');

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            // Add active class to clicked thumbnail
            thumb.classList.add('active');
            // Update main image
            mainImage.src = thumb.querySelector('img').src;
        });
    });

    // Xử lý sự kiện cho quantity controls
    const quantityInput = document.querySelector('.quantity-input');
    const decreaseBtn = document.querySelector('.quantity-btn.decrease');
    const increaseBtn = document.querySelector('.quantity-btn.increase');

    decreaseBtn.addEventListener('click', () => {
        let value = parseInt(quantityInput.value);
        if (value > 1) {
            quantityInput.value = value - 1;
        }
    });

    increaseBtn.addEventListener('click', () => {
        let value = parseInt(quantityInput.value);
        quantityInput.value = value + 1;
    });

    // Xử lý sự kiện cho nút thêm vào giỏ hàng
    const addToCartBtn = document.querySelector('.add-to-cart');
    addToCartBtn.addEventListener('click', () => {
        const quantity = parseInt(quantityInput.value);
        addToCart(productId, quantity);
    });

    // Xử lý sự kiện cho nút thêm vào danh sách yêu thích
    const addToWishlistBtn = document.querySelector('.add-to-wishlist');
    addToWishlistBtn.addEventListener('click', () => {
        addToWishlist(productId);
    });

    // Xử lý form đánh giá
    const reviewForm = document.querySelector('.review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', (e) => {
            e.preventDefault();
            submitReview(productId);
        });
    }
});

// Hàm load thông tin sản phẩm
async function loadProductDetails(productId) {
    try {
        const response = await fetch(`data/products.json`);
        const data = await response.json();
        const product = data.products.find(p => p.id === parseInt(productId));

        if (!product) {
            throw new Error('Product not found');
        }

        // Cập nhật thông tin sản phẩm
        document.querySelector('.product-detail__name').textContent = product.name;
        document.querySelector('.current-price').textContent = formatPrice(product.price);
        
        if (product.oldPrice) {
            document.querySelector('.old-price').textContent = formatPrice(product.oldPrice);
            const discount = Math.round((1 - product.price / product.oldPrice) * 100);
            document.querySelector('.discount').textContent = `-${discount}%`;
        }

        document.querySelector('.product-detail__description').textContent = product.description;
        document.querySelector('.product-detail__main-image img').src = product.image;

        // Cập nhật gallery
        const thumbnailsContainer = document.querySelector('.product-detail__thumbnails');
        thumbnailsContainer.innerHTML = '';
        product.images.forEach((image, index) => {
            const thumb = document.createElement('div');
            thumb.className = `thumbnail ${index === 0 ? 'active' : ''}`;
            thumb.innerHTML = `<img src="${image}" alt="${product.name}">`;
            thumbnailsContainer.appendChild(thumb);
        });

        // Cập nhật meta thông tin
        const metaContainer = document.querySelector('.product-detail__meta');
        metaContainer.innerHTML = '';
        Object.entries(product.meta).forEach(([label, value]) => {
            const metaItem = document.createElement('div');
            metaItem.className = 'meta-item';
            metaItem.innerHTML = `
                <span class="meta-label">${label}:</span>
                <span class="meta-value">${value}</span>
            `;
            metaContainer.appendChild(metaItem);
        });

        // Cập nhật thông số kỹ thuật
        const specsTable = document.querySelector('.specs-table tbody');
        specsTable.innerHTML = '';
        Object.entries(product.specifications).forEach(([key, value]) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${key}</td>
                <td>${value}</td>
            `;
            specsTable.appendChild(row);
        });

        // Cập nhật đánh giá
        updateReviews(product.reviews);

    } catch (error) {
        console.error('Error loading product details:', error);
        // Hiển thị thông báo lỗi cho người dùng
        alert('Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.');
    }
}

// Hàm load sản phẩm liên quan
async function loadRelatedProducts(productId) {
    try {
        const response = await fetch(`data/products.json`);
        const data = await response.json();
        const currentProduct = data.products.find(p => p.id === parseInt(productId));
        
        // Lọc sản phẩm cùng danh mục
        const relatedProducts = data.products
            .filter(p => p.categoryId === currentProduct.categoryId && p.id !== currentProduct.id)
            .slice(0, 4);

        const productsGrid = document.querySelector('.products-grid');
        productsGrid.innerHTML = '';

        relatedProducts.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card';
            productCard.innerHTML = `
                <a href="product-detail.html?id=${product.id}">
                    <img src="${product.image}" alt="${product.name}">
                    <h3>${product.name}</h3>
                    <p class="price">${formatPrice(product.price)}</p>
                </a>
            `;
            productsGrid.appendChild(productCard);
        });

    } catch (error) {
        console.error('Error loading related products:', error);
    }
}

// Hàm thêm vào giỏ hàng
function addToCart(productId, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.id === productId);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ id: productId, quantity });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Đã thêm sản phẩm vào giỏ hàng');
}

// Hàm thêm vào danh sách yêu thích
function addToWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    
    if (wishlist.includes(productId)) {
        wishlist = wishlist.filter(id => id !== productId);
        alert('Đã xóa sản phẩm khỏi danh sách yêu thích');
    } else {
        wishlist.push(productId);
        alert('Đã thêm sản phẩm vào danh sách yêu thích');
    }

    localStorage.setItem('wishlist', JSON.stringify(wishlist));
}

// Hàm cập nhật phần đánh giá
function updateReviews(reviews) {
    const reviewsList = document.querySelector('.reviews-list');
    const averageRating = document.querySelector('.rating');
    const totalReviews = document.querySelector('.total-reviews');

    // Tính điểm trung bình
    const avgRating = reviews.reduce((sum, review) => sum + review.rating, 0) / reviews.length;
    averageRating.textContent = avgRating.toFixed(1);
    totalReviews.textContent = `${reviews.length} đánh giá`;

    // Hiển thị danh sách đánh giá
    reviewsList.innerHTML = '';
    reviews.forEach(review => {
        const reviewItem = document.createElement('div');
        reviewItem.className = 'review-item';
        reviewItem.innerHTML = `
            <div class="review-header">
                <div class="reviewer-info">
                    <img src="${review.avatar}" alt="${review.name}" class="reviewer-avatar">
                    <span class="reviewer-name">${review.name}</span>
                </div>
                <div class="review-rating">
                    ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}
                </div>
            </div>
            <div class="review-content">${review.content}</div>
            <div class="review-date">${formatDate(review.date)}</div>
        `;
        reviewsList.appendChild(reviewItem);
    });
}

// Hàm gửi đánh giá
function submitReview(productId) {
    const rating = document.querySelector('input[name="rating"]:checked').value;
    const content = document.querySelector('textarea[name="review"]').value;

    if (!rating || !content) {
        alert('Vui lòng điền đầy đủ thông tin đánh giá');
        return;
    }

    // Trong thực tế, đây sẽ là một API call
    console.log('Submitting review:', { productId, rating, content });
    alert('Cảm ơn bạn đã đánh giá sản phẩm!');
}

// Hàm format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

// Hàm format ngày tháng
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
} 