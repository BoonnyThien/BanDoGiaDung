document.addEventListener('DOMContentLoaded', function() {
    // Lấy tham số từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('category');
    const page = parseInt(urlParams.get('page')) || 1;
    const sort = urlParams.get('sort') || 'popular';
    const minPrice = urlParams.get('minPrice');
    const maxPrice = urlParams.get('maxPrice');
    const brands = urlParams.getAll('brand');

    // Load sản phẩm
    loadProducts({
        categoryId,
        page,
        sort,
        minPrice,
        maxPrice,
        brands
    });

    // Xử lý sự kiện cho các bộ lọc
    const categoryLinks = document.querySelectorAll('.filter-section__link');
    categoryLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = link.getAttribute('data-category');
            updateFilters({ categoryId: category });
        });
    });

    // Xử lý sự kiện cho bộ lọc giá
    const priceForm = document.querySelector('.filter-section__price');
    priceForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const minPrice = document.querySelector('input[placeholder="Từ"]').value;
        const maxPrice = document.querySelector('input[placeholder="Đến"]').value;
        updateFilters({ minPrice, maxPrice });
    });

    // Xử lý sự kiện cho bộ lọc thương hiệu
    const brandCheckboxes = document.querySelectorAll('input[name="brand"]');
    brandCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedBrands = Array.from(brandCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            updateFilters({ brands: selectedBrands });
        });
    });

    // Xử lý sự kiện cho sắp xếp
    const sortSelect = document.querySelector('.category__sort-select');
    sortSelect.value = sort;
    sortSelect.addEventListener('change', () => {
        updateFilters({ sort: sortSelect.value });
    });

    // Xử lý sự kiện cho phân trang
    const paginationButtons = document.querySelectorAll('.pagination__btn');
    paginationButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (button.classList.contains('active')) return;
            
            const page = button.textContent;
            if (page) {
                updateFilters({ page: parseInt(page) });
            } else {
                const isNext = button.querySelector('.fa-chevron-right');
                const currentPage = document.querySelector('.pagination__btn.active').textContent;
                updateFilters({ page: parseInt(currentPage) + (isNext ? 1 : -1) });
            }
        });
    });
});

// Hàm load sản phẩm
async function loadProducts(filters) {
    try {
        const response = await fetch('data/products.json');
        const data = await response.json();
        
        // Lọc sản phẩm theo các tiêu chí
        let products = data.products;

        if (filters.categoryId) {
            products = products.filter(p => p.categoryId === parseInt(filters.categoryId));
        }

        if (filters.minPrice) {
            products = products.filter(p => p.price >= parseInt(filters.minPrice));
        }

        if (filters.maxPrice) {
            products = products.filter(p => p.price <= parseInt(filters.maxPrice));
        }

        if (filters.brands && filters.brands.length > 0) {
            products = products.filter(p => filters.brands.includes(p.brand));
        }

        // Sắp xếp sản phẩm
        switch (filters.sort) {
            case 'price-asc':
                products.sort((a, b) => a.price - b.price);
                break;
            case 'price-desc':
                products.sort((a, b) => b.price - a.price);
                break;
            case 'newest':
                products.sort((a, b) => new Date(b.date) - new Date(a.date));
                break;
            default: // popular
                products.sort((a, b) => b.rating - a.rating);
        }

        // Phân trang
        const itemsPerPage = 12;
        const totalPages = Math.ceil(products.length / itemsPerPage);
        const currentPage = filters.page || 1;
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedProducts = products.slice(startIndex, endIndex);

        // Hiển thị sản phẩm
        displayProducts(paginatedProducts);
        
        // Cập nhật phân trang
        updatePagination(currentPage, totalPages);

    } catch (error) {
        console.error('Error loading products:', error);
        alert('Không thể tải danh sách sản phẩm. Vui lòng thử lại sau.');
    }
}

// Hàm hiển thị sản phẩm
function displayProducts(products) {
    const productsGrid = document.querySelector('.products-grid');
    productsGrid.innerHTML = '';

    products.forEach(product => {
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
}

// Hàm cập nhật phân trang
function updatePagination(currentPage, totalPages) {
    const pagination = document.querySelector('.pagination');
    pagination.innerHTML = '';

    // Nút Previous
    const prevButton = document.createElement('button');
    prevButton.className = `pagination__btn ${currentPage === 1 ? 'disabled' : ''}`;
    prevButton.innerHTML = '<i class="fas fa-chevron-left"></i>';
    pagination.appendChild(prevButton);

    // Các nút số trang
    for (let i = 1; i <= totalPages; i++) {
        if (
            i === 1 || // Trang đầu
            i === totalPages || // Trang cuối
            (i >= currentPage - 2 && i <= currentPage + 2) // 2 trang trước và sau trang hiện tại
        ) {
            const pageButton = document.createElement('button');
            pageButton.className = `pagination__btn ${i === currentPage ? 'active' : ''}`;
            pageButton.textContent = i;
            pagination.appendChild(pageButton);
        } else if (
            i === currentPage - 3 || // Dấu ... trước
            i === currentPage + 3 // Dấu ... sau
        ) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'pagination__ellipsis';
            ellipsis.textContent = '...';
            pagination.appendChild(ellipsis);
        }
    }

    // Nút Next
    const nextButton = document.createElement('button');
    nextButton.className = `pagination__btn ${currentPage === totalPages ? 'disabled' : ''}`;
    nextButton.innerHTML = '<i class="fas fa-chevron-right"></i>';
    pagination.appendChild(nextButton);
}

// Hàm cập nhật bộ lọc
function updateFilters(newFilters) {
    const urlParams = new URLSearchParams(window.location.search);
    
    // Cập nhật các tham số
    Object.entries(newFilters).forEach(([key, value]) => {
        if (value) {
            if (Array.isArray(value)) {
                urlParams.delete(key);
                value.forEach(v => urlParams.append(key, v));
            } else {
                urlParams.set(key, value);
            }
        } else {
            urlParams.delete(key);
        }
    });

    // Reset về trang 1 khi thay đổi bộ lọc
    if (Object.keys(newFilters).some(key => key !== 'page')) {
        urlParams.set('page', '1');
    }

    // Cập nhật URL và load lại sản phẩm
    window.history.pushState({}, '', `${window.location.pathname}?${urlParams.toString()}`);
    loadProducts(Object.fromEntries(urlParams));
}

// Hàm format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
} 