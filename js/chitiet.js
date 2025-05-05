// static-version/js/chitiet.js
function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Hàm hiển thị thông báo trong khu vực sản phẩm
function toast({ title = '', message = '', type = 'success', duration = 1100, container }) {
    if (container) {
        const delay = (duration / 1000).toFixed(2);
        container.classList.add('display_flex');
        const toast = document.createElement('div');
        toast.classList.add('toast', `toast--${type}`);
        toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`;
        toast.innerHTML = `
            <div class="toast__icon">
                <i class="fa-brands fa-shopify"></i>
            </div>
            <div class="toast__body">
                <h3 class="toast__title">${title}</h3>
                <p class="toast__message">${message}</p>
            </div>
        `;
        container.appendChild(toast);

        setTimeout(function () {
            container.removeChild(toast);
            container.classList.remove('display_flex');
        }, duration + 1000);
    }
}

function showsuccess(message, container) {
    toast({
        title: 'SUCCESS !!!',
        message: message,
        type: 'success',
        container: container
    });
}

function showerror(message, container) {
    toast({
        title: 'WARNING !!!',
        message: message,
        type: 'error',
        container: container
    });
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
    const products = jsonData.tables.find(table => table.name === "tbl_product").data;
    const categories = jsonData.tables.find(table => table.name === "tbl_category").data;
    const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;
    const users = jsonData.tables.find(table => table.name === "tbl_user").data;
    const cart = jsonData.tables.find(table => table.name === "tbl_cart").data;

    // Lấy product_id từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('product_id');
    const product = products.find(p => p.product_id === productId);

    // Kiểm tra trạng thái đăng nhập (giả lập bằng localStorage)
    const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
    const userId = loggedInUser ? loggedInUser.user_id : "0";

    // Hiển thị thông tin chi tiết sản phẩm
    const productDetail = $('#product-detail');
    if (product) {
        const category = categories.find(cat => cat.category_id === product.category_id);
        const brand = brands.find(b => b.brand_id === product.brand_id);
        const discount = product.old_price > 0 ? Math.round(((product.old_price - product.new_price) / product.old_price) * 100) : 0;
        const discountClass = product.old_price < product.new_price ? 'display_none' : '';

        // Lấy sản phẩm liên quan
        const relatedProducts = products.filter(p => p.category_id === product.category_id && p.product_id !== product.product_id);

        productDetail.innerHTML = `
            <div class="product_detail-wrap grid">
                <div class="navigation">
                    <a href="index.html" class="navigation_child">Home</a>
                    <div style="display: inline-block;">
                        <i class="fa-solid fa-chevron-right"></i>
                        <a href="danhmuc.html?id_danhmuc=${product.category_id}" class="navigation_child">${category.category_name}</a>
                    </div>
                    <div style="display: inline-block;">
                        <i class="fa-solid fa-chevron-right"></i>
                        ${product.product_name}
                    </div>
                </div>
                <div class="grid__row product_detail-infor">
                    <div class="grid__column-4">
                        <div class="detail_infor-img">
                            <img class="img_main" src="./assets/img/${product.main_image}" alt="Sản phẩm">
                            <div class="img_sub-wrap">
                                <div class="img_sub">
                                    <img class="border_active" src="./assets/img/${product.main_image}" alt="Sub1">
                                </div>
                                <div class="img_sub">
                                    <img src="./assets/img/${product.extra_image1}" alt="">
                                </div>
                                <div class="img_sub">
                                    <img src="./assets/img/${product.extra_image2}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid__column-8 product_detail-background">
                        <!-- Container cho thông báo -->
                        <div class="product-toast" id="product-toast"></div>
                        <h1 class="product_detail-name">${product.product_name}</h1>
                        <div class="product_detail-quality">
                            <div class="product-item__rating" style="font-size: 1.6rem;">
                                <i class="product-item__star--gold fa-solid fa-star"></i>
                                <i class="product-item__star--gold fa-solid fa-star"></i>
                                <i class="product-item__star--gold fa-solid fa-star"></i>
                                <i class="product-item__star--gold fa-solid fa-star"></i>
                                <i class="product-item__star--gold fa-solid fa-star"></i>
                            </div>
                            <div class="product-item__evaluate">
                                <p>560</p> Đánh giá
                            </div>
                            <div class="product-item__sales">
                                <p>${product.quantity}</p> Đã bán
                            </div>
                        </div>
                        <div class="product_detail-price">
                            <p class="product_detail-price-old ${discountClass}">${product.old_price} <sup>đ</sup></p>
                            <p class="product_detail-price-new" data-price="${product.new_price}">${product.new_price} <sup>đ</sup></p>
                        </div>
                        <div class="product_detail-discount">
                            <span>Flash Sale giảm giá </span>
                            <p> Giảm ${discount} %</p>
                        </div>
                        <div class="product_detail_transport">
                            <span>Vận chuyển </span>
                            <p><i class="fa-solid fa-truck"></i>Miễn phí vận chuyển toàn quốc</p>
                        </div>
                        <div class="product_detail-number-wrap">
                            <span>Số lượng </span>
                            <div class="product_detail-number">
                                <div class="product_detail-buy_number decrease"><i class="fa-solid fa-minus"></i></div>
                                <input type="number" class="product_detail-buy-updown" min="1" value="1" data-product_id="${product.product_id}">
                                <div class="product_detail-buy_number increase"><i class="fa-solid fa-plus"></i></div>
                            </div>
                            <p>${product.quantity} sản phẩm có sẵn</p>
                        </div>
                        <div class="product_detail-button-group">
                            <div class="btn product_detail-button product_detail-btncart"><i class="fa-solid fa-cart-plus"></i>Thêm vào giỏ hàng</div>
                            <div class="btn product_detail-button product_detail-btnbuy"><i class="fa-solid fa-money-bill-wave"></i>Mua ngay</div>
                        </div>
                        <div class="product_detail-return">
                            <span><i class="fa-solid fa-shield-halved"></i>Chính sách trả hàng</span>
                            <p>Miễn phí đổi trả trong vòng 15 ngày từ khi nhận hàng</p>
                        </div>
                    </div>
                </div>
                <div class="grid__row product_specific">
                    <div class="grid__column-6" style="border-right: 2px solid #ccc;">
                        <div class="product_specific-infor">
                            <h1>Thông số chi tiết</h1>
                            <div class="product_specific-infor-wrap">
                                <span>Số lượng khuyến mãi</span>
                                <p>99</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Số lượng hàng còn lại</span>
                                <p>${product.quantity}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Xuất xứ</span>
                                <p>${product.origin}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Hạn bảo hành</span>
                                <p>${product.warranty_period || 'Không'}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Loại bảo hành</span>
                                <p>${product.warranty_option || 'Không'}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Tính năng</span>
                                <p>${product.features || 'Không'}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Chất liệu</span>
                                <p>${product.material || 'Không'}</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Điện áp đầu vào</span>
                                <p>Không</p>
                            </div>
                            <div class="product_specific-infor-wrap">
                                <span>Tiêu thụ điện năng</span>
                                <p>Không</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid__column-6">
                        <div class="product_specific-describe">
                            <h1>Mô tả sản phẩm</h1>
                            <p>${product.descriptions.replace(/\r\n/g, '<br>')}</p>
                        </div>
                    </div>
                </div>
                <div class="home-product" style="padding: 15px; background-color: rgb(245, 237, 230); border-radius: 20px;">
                    <div class="next_product"><i class="fa-solid fa-angle-right"></i></div>
                    <div class="before_product"><i class="fa-solid fa-angle-left"></i></div>
                    <div style="display: inline-block; padding: 10px 0px; margin: 10px 12px; font-size: 2.5rem; font-weight: 800; color: var(--primary-color); border-bottom: 2px solid var(--primary-color);">
                        Sản phẩm liên quan
                    </div>
                    <div style="overflow: hidden;">
                        <div class="grid_no_wrap" id="related-products"></div>
                    </div>
                </div>
            </div>
        `;

        // Hiển thị sản phẩm liên quan
        const relatedProductsContainer = $('#related-products');
        relatedProducts.forEach(relatedProduct => {
            const relatedBrand = brands.find(b => b.brand_id === relatedProduct.brand_id);
            const relatedDiscount = relatedProduct.old_price > 0 ? Math.round(((relatedProduct.old_price - relatedProduct.new_price) / relatedProduct.old_price) * 100) : 0;
            const relatedDiscountClass = relatedProduct.old_price < relatedProduct.new_price ? 'display_none' : '';
            const hotClass = relatedProduct.is_hot === "1" ? 'favourite_active' : '';

            const productDiv = document.createElement('div');
            productDiv.className = 'grid__column-2 product';
            productDiv.style.flexShrink = 0;
            productDiv.innerHTML = `
                <a class="product-item" href="chitiet.html?product_id=${relatedProduct.product_id}">
                    <div class="product-item__img" style="background-image: url(./assets/img/${relatedProduct.main_image});"></div>
                    <h4 class="product-item__name">${relatedProduct.product_name}</h4>
                    <div class="product-item__price">
                        <span class="product-item__price-old ${relatedDiscountClass}">${relatedProduct.old_price} <sup>đ</sup></span>
                        <span class="product-item__price-current">${relatedProduct.new_price} <sup>đ</sup></span>
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
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <span class="product-item__sold">${relatedProduct.quantity} đã bán</span>
                    </div>
                    <div class="product-item__origin">
                        <span class="product-item__brand">${relatedBrand.brand_name}</span>
                        <span class="product-item__origin-name">${relatedProduct.origin}</span>
                    </div>
                    <div class="product-item__favourite ${hotClass}">
                        <i class="fa-solid fa-check"></i>
                        <span>yêu thích</span>
                    </div>
                    <div class="product-item__sale-off ${relatedDiscountClass}">
                        <span class="product-item__sale-off-percent">${relatedDiscount} %</span>
                        <span class="product-item__sale-off-lable">Giảm</span>
                    </div>
                </a>
            `;
            relatedProductsContainer.appendChild(productDiv);
        });

        // Tăng giảm số lượng
        const decrease = $(".decrease");
        const increase = $(".increase");
        const inputElement = $(".product_detail-buy-updown");

        if (decrease && increase && inputElement) {
            decrease.addEventListener("click", function () {
                let value = parseInt(inputElement.value);
                if (value > 1) inputElement.value = value - 1;
            });

            increase.addEventListener("click", function () {
                let value = parseInt(inputElement.value);
                inputElement.value = value + 1;
            });
        }

        // Thay đổi ảnh chính hiển thị
        const imgMain = $(".img_main");
        const imgExtras = $$(".img_sub img");

        if (imgMain && imgExtras) {
            imgExtras.forEach((imgItem) => {
                imgItem.addEventListener("mouseenter", () => {
                    imgMain.src = imgItem.src;
                    imgExtras.forEach((item) =>
                        item.classList.toggle("border_active", item === imgItem)
                    );
                });
            });
        }

        // Thêm sản phẩm vào giỏ hàng (giả lập bằng localStorage)
        function updateQuantity(productId, newQuantity) {
            let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const existingItem = cartItems.find(item => item.product_id === productId && item.user_id === userId);

            if (existingItem) {
                existingItem.quantity = parseInt(existingItem.quantity) + parseInt(newQuantity);
            } else {
                cartItems.push({ user_id: userId, product_id: productId, quantity: newQuantity });
            }

            localStorage.setItem('cart', JSON.stringify(cartItems));
        }

        const btnAddCart = $(".product_detail-btncart");
        const toastContainer = $('#product-toast'); // Container cho thông báo
        if (btnAddCart) {
            btnAddCart.addEventListener("click", function () {
                const quantity = inputElement.value;
                const sp_id = inputElement.getAttribute("data-product_id");
                updateQuantity(sp_id, quantity);
                showsuccess("Thêm sản phẩm thành công!", toastContainer); // Hiển thị thông báo trong khu vực sản phẩm
                setTimeout(() => {
                    window.location.href = `chitiet.html?product_id=${sp_id}`;
                }, 2000);
            });
        }

        // Nút mua sản phẩm
        const btnAddOrder = $(".product_detail-btnbuy");
        if (btnAddOrder) {
            btnAddOrder.addEventListener("click", function () {
                if (userId !== "0") {
                    confirm_show();
                } else {
                    login_show();
                }
            });
        }

        // Xử lý form xác nhận
        function confirm_Address(name, phone, address) {
            let user = loggedInUser;
            if (user) {
                user.user_name = name;
                user.user_phone = phone;
                user.user_address = address;
                localStorage.setItem('loggedInUser', JSON.stringify(user));
            }
        }

        function addProduct_dh(orderCode, productId, numberBuy, onePrice) {
            let orders = JSON.parse(localStorage.getItem('orders')) || [];
            orders.push({
                transaction_code: orderCode,
                user_id: userId,
                product_id: productId,
                quantity: numberBuy,
                total_price: onePrice * numberBuy,
                status: "0",
                order_date: new Date().toISOString()
            });
            localStorage.setItem('orders', JSON.stringify(orders));
        }

        function processOrder_ct() {
            const randomNumber = Math.floor(Math.random() * 1000000);
            const orderCode = "DH" + randomNumber.toString().padStart(6, "0");
            const sp_id = inputElement.getAttribute("data-product_id");
            const quantity = inputElement.value;
            const onePrice = parseInt($(".product_detail-price-new").getAttribute("data-price"));

            addProduct_dh(orderCode, sp_id, quantity, onePrice);
        }

        const confirmBtnAdress = $('.btn_confirm-address');
        if (confirmBtnAdress) {
            confirmBtnAdress.addEventListener('click', function () {
                const name_confirm = document.getElementById('name_confirm').value;
                const phone_confirm = document.getElementById('phone_confirm').value;
                const address_confirm = document.getElementById('address_confirm').value;
                $(".modal").style.display = "none";

                processOrder_ct();
                showsuccess('Đặt mua thành công!', toastContainer); // Hiển thị thông báo trong khu vực sản phẩm
                setTimeout(() => {
                    confirm_Address(name_confirm, phone_confirm, address_confirm);
                    window.location.href = window.location.href;
                }, 2000);
            });
        }

        // Next/Previous sản phẩm liên quan
        const positionProduct = $$('.grid_no_wrap .product');
        const nextProduct = $('.next_product');
        const beforeProduct = $('.before_product');
        const containerProduct = $('.grid_no_wrap');
        const pageDetail = $('.product_detail');
        let currentIndex = 0;
        let styleLeft = 20;
        let maxIndex = positionProduct.length - 5;

        if (pageDetail && pageDetail.getAttribute('data-page') === 'detail') {
            styleLeft = 16.667;
            maxIndex = positionProduct.length - 6;
        }

        if (nextProduct && beforeProduct && containerProduct && positionProduct.length > 0) {
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

        // Hàm hiển thị form đăng nhập (giả lập từ header.js)
        function login_show() {
            const modal = $('.modal');
            const loginForm = $('.form-login');
            modal.style.display = 'flex';
            loginForm.style.display = 'block';
        }

        // Hàm hiển thị form xác nhận (giả lập từ header.js)
        function confirm_show() {
            const modal = $('.modal');
            const confirmForm = $('.form-confirm');
            modal.style.display = 'flex';
            confirmForm.style.display = 'block';
        }

        // Khởi tạo sự kiện cho header
        function initializeHeaderEvents() {
            // Xử lý đăng nhập (giả lập)
            const loginForm = $('.form-login');
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const account = loginForm.querySelector('input[name="login_account"]').value;
                    const password = loginForm.querySelector('input[name="login_password"]').value;
                    const user = users.find(u => u.user_account === account && u.user_pass === password);
                    if (user) {
                        localStorage.setItem('loggedInUser', JSON.stringify(user));
                        showsuccess('Đăng nhập thành công!', $('#toast')); // Hiển thị thông báo ở container chung
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        showerror('Tài khoản hoặc mật khẩu không đúng!', $('#toast')); // Hiển thị thông báo ở container chung
                    }
                });
            }

            // Cập nhật giỏ hàng trong header
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const cartCount = cartItems.reduce((total, item) => total + parseInt(item.quantity), 0);
            const cartCountElement = $('#cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
            }
        }

        initializeHeaderEvents();

        // Update user picture
        const userPicture = $('.user-picture');
        if (userPicture) {
            userPicture.src = `./assets/img/user/${loggedInUser.user_picture}`;
        }

    } else {
        productDetail.innerHTML = `<div class="grid"><p style="text-align: center; padding: 20px;">Sản phẩm không tồn tại.</p></div>`;
    }

}).catch(error => {
    console.error('Error loading data:', error);
});