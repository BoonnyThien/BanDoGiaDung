function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Tải header, footer và dữ liệu JSON
Promise.all([
    fetch('components/header.html').then(response => response.text()),
    fetch('components/footer.html').then(response => response.text()),
    fetch('../web_giadung.json').then(response => response.json())
]).then(([headerContent, footerContent, jsonData]) => {
    $('#header').innerHTML = headerContent;
    $('#footer').innerHTML = footerContent;

    // Lấy dữ liệu từ JSON
    const categories = jsonData.tables.find(table => table.name === "tbl_category").data;
    const products = jsonData.tables.find(table => table.name === "tbl_product").data;
    const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;

    // Lấy tham số từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const idDanhmuc = parseInt(urlParams.get('id_danhmuc')) || 1;
    const idThuonghieu = parseInt(urlParams.get('thuonghieu')) || 0;
    const startPrice = parseInt(urlParams.get('start')) || 0;
    const endPrice = parseInt(urlParams.get('end')) || 0;
    const currentPage = parseInt(urlParams.get('page')) || 1;

    // Hiển thị danh mục
    const categoryList = $('#category-list');
    categories.forEach(category => {
        const isActive = idDanhmuc === parseInt(category.category_id) ? 'category-item--active' : '';
        const li = document.createElement('li');
        li.className = `category-item ${isActive}`;
        li.setAttribute('data-id_danhmuc', category.category_id);
        li.innerHTML = `<a href="?id_danhmuc=${category.category_id}" class="category-item__link">${category.category_name}</a>`;
        categoryList.appendChild(li);
    });

    // Hiển thị thương hiệu
    const brandList = $('#brand-list');
    brands.forEach(brand => {
        const isActive = idThuonghieu === parseInt(brand.brand_id) ? 'check_active' : '';
        const li = document.createElement('li');
        li.className = `category-item-band ${isActive}`;
        li.setAttribute('data-id_thuonghieu', brand.brand_id);
        li.innerHTML = `
            <div class="band_check"><i class="fa-solid fa-check"></i></div>
            <span>${brand.brand_name}</span>
        `;
        brandList.appendChild(li);
    });

    // Hiển thị khoảng giá từ URL
    const startPriceInput = $('.price_start');
    const endPriceInput = $('.price_end');
    startPriceInput.value = startPrice;
    endPriceInput.value = endPrice;

    // Lọc sản phẩm
    let filteredProducts = products.filter(product => parseInt(product.category_id) === idDanhmuc);
    if (idThuonghieu) {
        filteredProducts = filteredProducts.filter(product => parseInt(product.brand_id) === idThuonghieu);
    }
    if (startPrice || endPrice) {
        if (startPrice < endPrice) {
            filteredProducts = filteredProducts.filter(product => 
                parseInt(product.new_price) >= startPrice && parseInt(product.new_price) <= endPrice
            );
        } else {
            filteredProducts = filteredProducts.filter(product => parseInt(product.new_price) >= startPrice);
        }
    }

    // Phân trang
    const itemsPerPage = 25;
    const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
    const offset = (currentPage - 1) * itemsPerPage;
    const paginatedProducts = filteredProducts.slice(offset, offset + itemsPerPage);

    // Hiển thị tiêu đề danh mục
    const categoryTitle = $('#category-title');
    const selectedCategory = categories.find(cat => parseInt(cat.category_id) === idDanhmuc);
    categoryTitle.textContent = selectedCategory ? selectedCategory.category_name : '';

    // Hiển thị sản phẩm
    const productList = $('#product-list');
    if (paginatedProducts.length === 0) {
        productList.innerHTML = `
            <div style="width: 100%; height: 400px; background-color: white; padding: 20px; margin: 15px; text-align: center;">
                <h1 style="width: 100%;">Không có sản phẩm</h1>
                <img src="../assets/img/no_cart.png" alt="Không có sản phẩm" style="display: block; margin: 0 auto;">
            </div>
        `;
    } else {
        paginatedProducts.forEach(product => {
            const brand = brands.find(b => b.brand_id === product.brand_id);
            const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
            const hotClass = product.is_hot === "1" ? 'favourite_active' : '';
            const discountClass = parseInt(product.old_price) < parseInt(product.new_price) ? 'display_none' : '';

            const productDiv = document.createElement('div');
            productDiv.className = 'grid__column-2-4';
            productDiv.innerHTML = `
                <a class="product-item" href="chitiet.html?product_id=${product.product_id}" data-id="${product.product_id}" data-price="${product.new_price}" data-soluong="${product.quantity}">
                    <div class="product-item__img" style="background-image: url(../assets/img/${product.main_image});"></div>
                    <h4 class="product-item__name">${product.product_name}</h4>
                    <div class="product-item__price">
                        <span class="product-item__price-old ${discountClass}">${product.old_price}<sup>đ</sup></span>
                        <span class="product-item__price-current">${product.new_price}<sup>đ</sup></span>
                    </div>
                    <div class="product-item__ation">
                        <span class="product-item__like">
                            <i class="product-item__like-icon-empty fa-regular fa-heart" style="color: #000;"></i>
                            <i class="product-item__like-icon-fill fa-solid fa-heart"></i>
                        </span>
                        <div class="product-item__rating">
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                        </div>
                        <span class="product-item__sold">${product.quantity} đã bán</span>
                    </div>
                    <div class="product-item__origin">
                        <span class="product-item__brand">${brand.brand_name}</span>
                        <span class="product-item__origin-name">${product.origin}</span>
                    </div>
                    <div class="product-item__favourite ${hotClass}">
                        <i class="fa-solid fa-check"></i>
                        <span>yêu thích</span>
                    </div>
                    <div class="product-item__sale-off ${discountClass}">
                        <span class="product-item__sale-off-percent">${discount}%</span>
                        <span class="product-item__sale-off-lable">Giảm</span>
                    </div>
                </a>
            `;
            productList.appendChild(productDiv);
        });
    }

    // Hiển thị thông tin phân trang
    $('#current-page').textContent = currentPage;
    $('#total-pages').textContent = totalPages;

    // Tạo phân trang
    const pagination = $('#pagination');
    const prevPage = document.createElement('li');
    prevPage.className = 'pagination-item';
    prevPage.innerHTML = `<a href="?page=${currentPage - 1}" class="pagination-item__link ${currentPage > 1 ? '' : 'a--disable'}"><i class="pagination-item__icon fa-solid fa-angle-left"></i></a>`;
    pagination.appendChild(prevPage);

    for (let num = 1; num <= totalPages; num++) {
        const pageItem = document.createElement('li');
        pageItem.className = `pagination-item ${currentPage === num ? 'pagination-item--active' : ''}`;
        pageItem.innerHTML = `<a href="?page=${num}" class="pagination-item__link">${num}</a>`;
        pagination.appendChild(pageItem);
    }

    const nextPage = document.createElement('li');
    nextPage.innerHTML = `<a href="?page=${currentPage + 1}" class="pagination-item__link ${currentPage < totalPages ? '' : 'a--disable'}"><i class="pagination-item__icon fa-solid fa-angle-right"></i></a>`;
    pagination.appendChild(nextPage);

    // Xử lý sự kiện chọn thương hiệu
    const brandItems = $$('.category-item-band');
    brandItems.forEach(brand => {
        brand.addEventListener('click', () => {
            const brandValue = brand.getAttribute('data-id_thuonghieu');
            brandItems.forEach(otherBrand => otherBrand.classList.remove('check_active'));
            brand.classList.toggle('check_active');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('thuonghieu', brandValue);
            window.location.href = currentUrl.toString();
        });
    });

    // Slide chuyển động
    const imgPosition = $$('.slide_header-169 img');
    const imgContainer = $('.slide_header-169');
    const dotItems = $$('.slider_header-dot');

    if (imgPosition && imgContainer && dotItems) {
        let index = 0;
        let interval;

        function startSlider() {
            interval = setInterval(imgSlider, 3000);
        }

        function imgSlider() {
            index = (index + 1) % imgPosition.length;
            slide(index);
        }

        function slide(idx) {
            imgContainer.style.left = `-${idx * 100}%`;
            dotItems.forEach(dot => dot.classList.remove('dot_active'));
            dotItems[idx].classList.add('dot_active');
        }

        imgPosition.forEach((image, idx) => {
            image.style.left = `${idx * 100}%`;
        });

        dotItems.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                index = idx;
                slide(index);
                clearInterval(interval);
                startSlider();
            });
        });

        startSlider();
    }

    // Click yêu thích
    $$('.product-item__like').forEach(item => {
        item.addEventListener('click', event => {
            event.preventDefault();
            item.classList.toggle('product-item__like--liked');
        });
    });

    // Sắp xếp sản phẩm
    const btnFilters = $$('.home-filter button');
    btnFilters.forEach((btnFilter, index) => {
        btnFilter.addEventListener('click', () => {
            btnFilters.forEach(btn => btn.classList.remove('btn--primary'));
            btnFilter.classList.add('btn--primary');

            const productsElement = Array.from($$('.parent_product .product-item'));
            if (index === 0) {
                productsElement.sort((a, b) => b.dataset.soluong - a.dataset.soluong);
            } else if (index === 1) {
                productsElement.sort((a, b) => b.dataset.id - a.dataset.id);
            }

            const parent = $('.parent_product');
            parent.innerHTML = '';
            productsElement.forEach(product => {
                const productWrap = document.createElement('div');
                productWrap.className = 'grid__column-2-4';
                productWrap.appendChild(product);
                parent.appendChild(productWrap);
            });
        });
    });

    // Sắp xếp theo giá
    const selectPrices = $$('.select-input__item a');
    const spanSortPrice = $('.select-input__label');

    selectPrices.forEach((selectPrice, index) => {
        selectPrice.addEventListener('click', event => {
            event.preventDefault();
            spanSortPrice.textContent = selectPrice.textContent;

            const productsElement = Array.from($$('.parent_product .product-item'));
            if (index === 0) {
                productsElement.sort((a, b) => a.dataset.price - b.dataset.price);
            } else if (index === 1) {
                productsElement.sort((a, b) => b.dataset.price - a.dataset.price);
            }

            const parent = $('.parent_product');
            parent.innerHTML = '';
            productsElement.forEach(product => {
                const productWrap = document.createElement('div');
                productWrap.className = 'grid__column-2-4';
                productWrap.appendChild(product);
                parent.appendChild(productWrap);
            });
        });
    });

    // Tìm theo khoảng giá
    const startPriceInputEvent = $('.price_start');
    const endPriceInputEvent = $('.price_end');
    const checkPriceBtn = $('.check-price-btn');

    checkPriceBtn.addEventListener('click', () => {
        const start = startPriceInputEvent.value;
        const end = endPriceInputEvent.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('start', start);
        currentUrl.searchParams.set('end', end);
        window.location.href = currentUrl.toString();
    });

}).catch(error => {
    console.error('Error loading data:', error);
});

// Hàm khởi tạo sự kiện cho header (nếu cần)
function initializeHeaderEvents() {
    // Thêm các sự kiện cho header nếu cần
}