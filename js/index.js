// static-version/js/index.js

function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Tải header, footer và dữ liệu JSON
Promise.all([
    fetch('./static-version/components/header.html').then(response => response.text()),
    fetch('./static-version/components/footer.html').then(response => response.text()),
    fetch('./web_giadung.json').then(response => response.json())
]).then(([headerContent, footerContent, jsonData]) => {
    $('#header').innerHTML = headerContent;
    $('#footer').innerHTML = footerContent;

    // Lấy dữ liệu từ JSON
    const categories = jsonData.tables.find(table => table.name === "tbl_category").data;
    const products = jsonData.tables.find(table => table.name === "tbl_product").data;
    const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;

    // Thêm danh mục vào danh sách
    const categoryList = $('#category-list');
    categories.forEach(category => {
        const li = document.createElement('li');
        li.className = 'category-item';
        li.innerHTML = `<a href="danhmuc.html?id_danhmuc=${category.category_id}" class="category-item__link">${category.category_name}</a>`;
        categoryList.appendChild(li);
    });

    // Hiển thị sản phẩm HOT
    const hotProductsList = $('#hot-products-list');
    const hotProducts = products.filter(product => product.is_hot === "1");
    hotProducts.forEach(product => {
        const brand = brands.find(b => b.brand_id === product.brand_id);
        const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
        const hotClass = product.is_hot === "1" ? 'favourite_active' : '';
        const discountClass = product.old_price < product.new_price ? 'display_none' : '';

        const productDiv = document.createElement('div');
        productDiv.className = 'grid__column-2-4 product';
        productDiv.style.flexShrink = 0;
        productDiv.innerHTML = `
            <a class="product-item" href="chitiet.html?product_id=${product.product_id}">
                <div class="product-item__img" style="background-image: url(./assets/img/${product.main_image}); loading: 'lazy';"></div>
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
        hotProductsList.appendChild(productDiv);
    });

    // Phân trang và hiển thị sản phẩm gợi ý
    const itemsPerPage = 25;
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = parseInt(urlParams.get('page')) || 1;
    const totalPages = Math.ceil(products.length / itemsPerPage);
    const offset = (currentPage - 1) * itemsPerPage;

    const suggestedProducts = $('#suggested-products');
    const searchQuery = urlParams.get('search') || '';
    const suggestionTitle = $('#suggestion-title');
    suggestionTitle.textContent = searchQuery ? `Tìm kiếm: ${searchQuery}` : 'Gợi ý hôm nay';

    const filteredProducts = searchQuery
        ? products.filter(product => product.product_name.toLowerCase().includes(searchQuery.toLowerCase()))
        : products;

    const paginatedProducts = filteredProducts.slice(offset, offset + itemsPerPage);

    if (paginatedProducts.length === 0) {
        suggestedProducts.innerHTML = `
            <div style="width: 100%; background-color: white; padding: 20px; margin: 15px; text-align: center;">
                <h1 style="width: 100%;">Không có sản phẩm</h1>
                <img src="./assets/img/no_cart.png" alt="Không có sản phẩm" style="display: block; margin: 0 auto;">
            </div>
        `;
    } else {
        paginatedProducts.forEach(product => {
            const brand = brands.find(b => b.brand_id === product.brand_id);
            const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
            const hotClass = product.is_hot === "1" ? 'favourite_active' : '';
            const discountClass = product.old_price < product.new_price ? 'display_none' : '';

            const productDiv = document.createElement('div');
            productDiv.className = 'grid__column-2-4';
            productDiv.innerHTML = `
                <a class="product-item" href="chitiet.html?product_id=${product.product_id}">
                    <div class="product-item__img" style="background-image: url(./assets/img/${product.main_image});"></div>
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
            suggestedProducts.appendChild(productDiv);
        });
    }

    // Phân trang
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

    // Danh mục fixed vị trí
    const category = $('.category');
    const gridColumn = $('.div_cha');
    let lastScrollPosition = 0;

    if (category && gridColumn) {
        window.addEventListener('scroll', () => {
            const scrollPosition = window.pageYOffset;
            const categoryRect = category.getBoundingClientRect();
            const gridRect = gridColumn.getBoundingClientRect();

            if (gridRect.top <= 80 && gridRect.bottom - categoryRect.height >= 120) {
                category.style.position = 'fixed';
                category.style.top = scrollPosition >= lastScrollPosition ? '80px' : '120px';
                category.style.bottom = 'auto';
            } else {
                category.style.position = 'absolute';
                category.style.top = '0';
                category.style.bottom = 'auto';
            }
            lastScrollPosition = scrollPosition;
        });
    }

    // Click yêu thích
    const likeButtons = $$('.product-item__like');
    likeButtons.forEach(item => {
        item.addEventListener('click', (event) => {
            event.preventDefault();
            item.classList.toggle('product-item__like--liked');
        });
    });

    // Slide chuyển động
    const imgPosition = $$('.slide_header-169 img');
    const imgContainer = $('.slide_header-169');
    const dotItems = $$('.slider_header-dot');

    if (imgPosition && imgContainer && dotItems) {
        let index = 0;
        let interval;

        imgPosition.forEach((image, idx) => {
            image.style.left = `${idx * 100}%`;
        });

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

    // Next/Previous sản phẩm
    const positionProduct = $$('.grid_no_wrap .product');
    const nextProduct = $('.next_product');
    const beforeProduct = $('.before_product');
    const containerProduct = $('.grid_no_wrap');
    const pageDetail = $('.product_detail');

    if (nextProduct && beforeProduct && containerProduct && positionProduct.length > 0) {
        let currentIndex = 0;
        let styleLeft = 20;
        let maxIndex = positionProduct.length - 5;

        if (pageDetail && pageDetail.getAttribute('data-page') === 'detail') {
            styleLeft = 16.667;
            maxIndex = positionProduct.length - 6;
        }

        nextProduct.addEventListener('click', () => {
            if (currentIndex < maxIndex) {
                currentIndex++;
                containerProduct.style.left = `-${currentIndex * styleLeft}%`;
            }
        });

        beforeProduct.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                containerProduct.style.left = `-${currentIndex * styleLeft}%`;
            }
        });
    }
    // Trong component Home
class Home extends Component {
    state = {
        categories: [],
        products: [],
        brands: [],
        currentPage: 1,
        itemsPerPage: 25,
        filteredProducts: []
    };

    componentDidMount() {
        fetch('./web_giadung.json')
            .then(response => response.json())
            .then(jsonData => {
                const categories = jsonData.tables.find(table => table.name === "tbl_category").data;
                const products = jsonData.tables.find(table => table.name === "tbl_product").data;
                const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;

                this.setState({ categories, products, brands });

                // Khởi tạo Web Worker
                const worker = new Worker('./js/searchWorker.js');
                const urlParams = new URLSearchParams(window.location.search);
                const searchQuery = urlParams.get('search') || '';

                worker.postMessage({ products, searchQuery });
                worker.onmessage = (e) => {
                    this.setState({ filteredProducts: e.data });
                };

                this.initializeEvents();
            });

        const urlParams = new URLSearchParams(window.location.search);
        const page = parseInt(urlParams.get('page')) || 1;
        this.setState({ currentPage: page });
    }

    render() {
        const { categories, products, brands, currentPage, itemsPerPage, filteredProducts } = this.state;

        const hotProducts = products.filter(product => product.is_hot === "1");
        const totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
        const offset = (currentPage - 1) * itemsPerPage;
        const paginatedProducts = filteredProducts.slice(offset, offset + itemsPerPage);

        // ... (giữ nguyên phần render)
    }
}
    const { h, render } = window.preact;
    const { Router } = window.preactRouter;

    const App = () => {
        return h(Router, null,
            h(Home, { path: '/' }),
            h(async () => {
                const module = await import('./components/DanhMuc.js');
                return module.default;
            }, { path: '/danhmuc/:id' }),
            h(async () => {
                const module = await import('./components/ChiTiet.js');
                return module.default;
            }, { path: '/chitiet/:id' })
        );
    };

    render(h(App), document.getElementById('app'));

    
    // Hàm khởi tạo sự kiện cho header (nếu cần)
    function initializeHeaderEvents() {
        // Thêm các sự kiện cho header nếu cần, ví dụ: tìm kiếm, đăng nhập, đăng ký
    }
    // Thêm vào cuối index.js
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js')
                .then(registration => {
                    console.log('Service Worker registered:', registration);
                })
                .catch(error => {
                    console.error('Service Worker registration failed:', error);
                });
        });
    }
}).catch(error => {
    console.error('Error loading data:', error);
});