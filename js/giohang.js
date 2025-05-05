// static-version/js/giohang.js
function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Hàm hiển thị thông báo (tích hợp từ message.js)
function toast({ title = '', message = '', type = 'success', duration = 1100 }) {
    const main = document.getElementById('toast');
    if (main) {
        const delay = (duration / 1000).toFixed(2);
        main.classList.add('display_flex');
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
        main.appendChild(toast);

        setTimeout(function () {
            main.removeChild(toast);
            main.classList.remove('display_flex');
        }, duration + 1000);
    }
}

function showsuccess(message) {
    toast({
        title: 'SUCCESS !!!',
        message: message,
        type: 'success'
    });
}

function showerror(message) {
    toast({
        title: 'WARNING !!!',
        message: message,
        type: 'error'
    });
}

// Hàm hiển thị form đăng nhập (từ header.js)
function login_show() {
    const modal = $('.modal');
    const loginForm = $('.form-login');
    const registerForm = $('.form-register');
    const confirmForm = $('.form-confirm');
    if (modal && loginForm) {
        modal.style.display = 'flex';
        loginForm.style.display = 'block';
        if (registerForm) registerForm.style.display = 'none';
        if (confirmForm) confirmForm.style.display = 'none';
    }
}

// Hàm hiển thị form xác nhận (từ header.js)
function confirm_show() {
    const modal = $('.modal');
    const loginForm = $('.form-login');
    const registerForm = $('.form-register');
    const confirmForm = $('.form-confirm');
    if (modal && confirmForm) {
        modal.style.display = 'flex';
        confirmForm.style.display = 'block';
        if (loginForm) loginForm.style.display = 'none';
        if (registerForm) registerForm.style.display = 'none';

        // Điền thông tin người dùng vào form xác nhận nếu đã đăng nhập
        const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser'));
        if (loggedInUser) {
            $('#name_confirm').value = loggedInUser.user_name || '';
            $('#phone_confirm').value = loggedInUser.user_phone || '';
            $('#address_confirm').value = loggedInUser.user_address || '';
        }
    }
}

// Tải header, footer và dữ liệu JSON
Promise.all([
    fetch('./static-version/components/header.html').then(response => response.text()),
    fetch('./static-version/components/footer.html').then(response => response.text()),
    fetch('./web_giadung.json').then(response => response.text())
]).then(([headerContent, footerContent, jsonData]) => {
    $('#header').innerHTML = headerContent;
    $('#footer').innerHTML = footerContent;
    const data = JSON.parse(jsonData);
    const products = data.tables.find(table => table.name === "tbl_product").data;

    // Kiểm tra trạng thái đăng nhập
    const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
    const userId = loggedInUser ? loggedInUser.user_id : "0";

    // Lấy giỏ hàng từ localStorage
    let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const userCartItems = cartItems.filter(item => item.user_id === userId);

    // Render nội dung giỏ hàng
    const cartContent = $('#cart-content');
    let cartHtml = `
        <div class="cart_container">
            <div class="grid">
                <div class="navigation">
                    <a href="index.html" class="navigation_child">Home</a>
                    <div style="display: inline-block;">
                        <i class="fa-solid fa-chevron-right"></i>
                        Giỏ hàng
                    </div>
                </div>
                <div class="grid__row">
                    <div class="grid__column-8">
                        <div class="cart_all">
                            <div class="cart_select-all">
                                <div class="input_wrap">
                                    <input type="checkbox">
                                </div>
                                <p>Chọn tất cả (<span>0</span>)</p>
                            </div>
                            <div class="cart_remove-wrap">
                                <i class="fa-regular fa-thumbs-up"></i>
                            </div>
                        </div>
                        <div class="all_product-result">
    `;

    // Hiển thị sản phẩm trong giỏ hàng
    if (userCartItems.length > 0) {
        cartHtml += userCartItems.map(item => {
            const product = products.find(p => p.product_id === item.product_id);
            if (!product) return '';
            const display = product.old_price <= 0 ? 'display_none' : '';
            return `
                <div class="cart_product-wrap" data-product-id="${item.product_id}" data-product-old-price="${product.old_price == 0 ? product.new_price : product.old_price}" data-product-new-price="${product.new_price}">
                    <div class="cart_product-select">
                        <div class="input_wrap">
                            <input type="checkbox">
                        </div>
                        <div class="img_wrap">
                            <img src="./assets/img/${product.main_image}" alt="san pham">
                        </div>
                    </div>
                    <div class="cart_product-infor-wrap">
                        <div class="cart-infor">
                            <span class="cart-infor-name">${product.product_name}</span>
                            <div class="cartt-infor-price">
                                <span class="cart-infor-price-old" style="font-size: 1.8rem; color: #aaa; text-decoration: line-through;" class="${display}">
                                    ${product.old_price} <sup>đ</sup>
                                </span>
                                <span class="cart-infor-price-new" style="font-size: 2.2rem; color: var(--primary-color);">
                                    ${product.new_price} <sup>đ</sup>
                                </span>
                            </div>
                        </div>
                        <div class="cart_product-infor-number">
                            <div class="product_detail-number" style="margin-right: 20px;">
                                <div class="product_detail-buy_number decrease"><i class="fa-solid fa-minus"></i></div>
                                <input type="number" class="product_detail-buy-updown" min="1" value="${item.quantity}">
                                <div class="product_detail-buy_number increase"><i class="fa-solid fa-plus"></i></div>
                            </div>
                            <div class="cart_remove-wrap">
                                <i class="fa-solid fa-trash-can"></i>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    } else {
        cartHtml += `
            <div style="width: 100%; height: 100%; background-color: white; padding: 10px; border-radius: 20px; text-align: center;">
                <h1 style="width: 100%;">Không có sản phẩm</h1>
                <img src="./assets/img/no_cart.png" alt="Không có sản phẩm" style="display: block; margin: 0 auto;">
            </div>
        `;
    }

    cartHtml += `
                        </div>
                    </div>
                    <div class="grid__column-4">
                        <div class="cart_confirm">
                            <div class="cart_confirm-wrap">
                                <div>
                                    <i class="fa-solid fa-money-check-dollar"></i>
                                    <p>Chọn hoặc nhập ưu đãi</p>
                                </div>
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>
                            <div class="cart_confirm-wrap">
                                <div>
                                    <i class="fa-brands fa-bitcoin" style="color:yellow;"></i>
                                    <p>Đổi 0 điểm (~0đ)</p>
                                </div>
                                <i class="fa-solid fa-toggle-off toggle_off"></i>
                                <i class="fa-solid fa-toggle-on toggle_on"></i>
                            </div>
                            <div class="cart_confirm-infor">
                                <div class="cart_confirm-infor-wrap">
                                    <h1>Thông tin đơn hàng</h1>
                                </div>
                                <div class="cart_confirm-infor-wrap">
                                    <p>Tổng tiền</p>
                                    <span class="totalPrice">0 <sup>đ</sup></span>
                                </div>
                                <div class="cart_confirm-infor-wrap">
                                    <p>Tổng khuyến mãi</p>
                                    <span class="discoundPrice">0 <sup>đ</sup></span>
                                </div>
                                <div class="cart_confirm-infor-wrap">
                                    <p>Phí vận chuyển</p>
                                    <span>Miễn phí</span>
                                </div>
                                <div class="cart_confirm-infor-wrap">
                                    <p>Cần thanh toán</p>
                                    <span class="pay_element" style="color: var(--primary-color);">0 <sup>đ</sup></span>
                                </div>
                                <div class="cart_confirm-infor-wrap">
                                    <p>Điểm thưởng</p>
                                    <span><i class="fa-brands fa-bitcoin" style="color:yellow;"></i>+0</span>
                                </div>
                                <div class="cart_confirm-btn" data-user-id="${userId}">Đặt hàng</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    cartContent.innerHTML = cartHtml;

    // Xử lý checkbox chọn sản phẩm
    const selectAllCheckbox = $('.cart_select-all input[type="checkbox"]');
    const checkboxes = $$('.cart_product-select input[type="checkbox"]');
    const numberCheck = $(".cart_select-all p span");
    const payElement = $(".pay_element");
    const totalPriceElement = $(".totalPrice");
    const discountPriceElement = $(".discoundPrice");
    const cartNotice = $('#cart-count');

    let i = 0;

    // Hàm đếm số sản phẩm đang chọn
    function updateCheckedCount() {
        i = 0;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                ++i;
            }
        });
        if (numberCheck) numberCheck.innerText = i;
    }

    // Hàm tính tổng tiền của các sản phẩm được chọn
    function updateTotalPrice() {
        let payPrice = 0;
        let totalPrice = 0;
        let discountPrice = 0;
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const product = checkbox.closest(".cart_product-wrap");
                const quantity = product.querySelector(".product_detail-buy-updown").value;
                const new_price = product.getAttribute("data-product-new-price");
                payPrice += quantity * new_price;
                const old_price = product.getAttribute("data-product-old-price");
                totalPrice += quantity * old_price;
                discountPrice = totalPrice - payPrice;
            }
        });
        if (payElement) payElement.innerText = new Intl.NumberFormat("vi-VN").format(payPrice) + " ₫";
        if (totalPriceElement) totalPriceElement.innerText = new Intl.NumberFormat("vi-VN").format(totalPrice) + " ₫";
        if (discountPriceElement) discountPriceElement.innerText = new Intl.NumberFormat("vi-VN").format(discountPrice) + " ₫";
    }

    // Cập nhật tổng tiền và số lượng sản phẩm khi chọn/bỏ chọn
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            updateCheckedCount();
            updateTotalPrice();
            if (Array.from(checkboxes).every((chk) => chk.checked)) {
                selectAllCheckbox.checked = true;
            } else {
                selectAllCheckbox.checked = false;
            }
        });
    });

    // Xử lý chọn tất cả
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", function () {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = this.checked;
            });
            updateCheckedCount();
            updateTotalPrice();
        });
    }

    // Cập nhật số lượng sản phẩm
    function updateQuantity(productId, newQuantity) {
        cartItems = cartItems.map(item => {
            if (item.product_id === productId && item.user_id === userId) {
                return { ...item, quantity: newQuantity };
            }
            return item;
        });
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }

    $$(".decrease").forEach(function (decrease) {
        decrease.addEventListener("click", function () {
            const inputElement = this.closest(".product_detail-number").querySelector(".product_detail-buy-updown");
            const productId = this.closest(".cart_product-wrap").getAttribute("data-product-id");
            let value = parseInt(inputElement.value);
            if (value > 1) {
                inputElement.value = value - 1;
                updateQuantity(productId, inputElement.value);
                updateTotalPrice();
            }
        });
    });

    $$(".increase").forEach(function (increase) {
        increase.addEventListener("click", function () {
            const inputElement = this.closest(".product_detail-number").querySelector(".product_detail-buy-updown");
            const productId = this.closest(".cart_product-wrap").getAttribute("data-product-id");
            let value = parseInt(inputElement.value);
            inputElement.value = value + 1;
            updateQuantity(productId, inputElement.value);
            updateTotalPrice();
        });
    });

    // Xóa sản phẩm khỏi giỏ hàng
    function deleteProduct_gh(productId) {
        cartItems = cartItems.filter(item => !(item.product_id === productId && item.user_id === userId));
        localStorage.setItem('cart', JSON.stringify(cartItems));
    }

    const trashElements = $$(".cart_product-infor-number .cart_remove-wrap");
    const cart_notifis = $$(".header__cart-item");
    const notifiProduct_list = $(".header__cart-list");
    const cartPosition = $(".all_product-result");

    trashElements.forEach(function (trashElement) {
        trashElement.addEventListener("click", function () {
            const product = trashElement.closest(".cart_product-wrap");
            const id_xoa = product.getAttribute("data-product-id");
            product.classList.add("display_none");

            // Cập nhật giỏ hàng trong header
            cart_notifis.forEach(function (cart_notifi) {
                if (cart_notifi.getAttribute("data-product-id") == id_xoa) {
                    cart_notifi.classList.add("display_none");
                }
            });

            // Bỏ chọn các checkbox
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
                selectAllCheckbox.checked = false;
                if (numberCheck) numberCheck.innerText = 0;
                updateTotalPrice();
            });

            // Xóa sản phẩm khỏi localStorage
            deleteProduct_gh(id_xoa);
            showsuccess("Xóa sản phẩm thành công !!!");

            // Kiểm tra xem giỏ hàng còn sản phẩm không
            const remainingItems = cartItems.filter(item => item.user_id === userId);
            cartNotice.textContent = Math.max(0, parseInt(cartNotice.textContent) - 1);
            if (remainingItems.length === 0) {
                notifiProduct_list.classList.add("header__cart-list--no-cart");
                cartPosition.innerHTML = `
                    <div style="width: 100%; height: 100%; background-color: white; padding: 10px; border-radius: 20px; text-align: center;">
                        <h1 style="width: 100%;">Không có sản phẩm</h1>
                        <img src="./assets/img/no_cart.png" alt="Không có sản phẩm" style="display: block; margin: 0 auto;">
                    </div>`;
            }
        });
    });

    // Thêm sản phẩm vào đơn hàng và xóa khỏi giỏ hàng
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

    function processOrder_gh(checkboxes) {
        const randomNumber = Math.floor(Math.random() * 1000000);
        const orderCode = "DH" + randomNumber.toString().padStart(6, "0");

        if (checkboxes) {
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    const product = checkbox.closest(".cart_product-wrap");
                    const onePrice = product.getAttribute("data-product-old-price");
                    const product_id = product.getAttribute("data-product-id");
                    const numberBuy = product.querySelector(".product_detail-buy-updown").value;

                    // Thêm sản phẩm vào đơn hàng và xóa khỏi giỏ hàng
                    addProduct_dh(orderCode, product_id, numberBuy, onePrice);
                    deleteProduct_gh(product_id);
                }
            });
        }
    }

    // Cập nhật địa chỉ người dùng
    function confirm_Address(name, phone, address) {
        if (loggedInUser) {
            loggedInUser.user_name = name;
            loggedInUser.user_phone = phone;
            loggedInUser.user_address = address;
            localStorage.setItem('loggedInUser', JSON.stringify(loggedInUser));
        }
    }

    // Xử lý nút đặt hàng
    const btn_buy = $(".cart_confirm-btn");
    if (btn_buy) {
        btn_buy.addEventListener("click", function () {
            if (parseInt(numberCheck.textContent) !== 0) {
                if (userId !== "0") {
                    confirm_show();
                } else {
                    login_show();
                }
            } else {
                showerror("Chưa chọn sản phẩm nào!");
            }
        });
    }

    // Xử lý form xác nhận
    const confirmBtnAdress = $(".btn_confirm-address");
    if (confirmBtnAdress) {
        confirmBtnAdress.addEventListener("click", function () {
            const name_confirm = document.getElementById("name_confirm").value;
            const phone_confirm = document.getElementById("phone_confirm").value;
            const address_confirm = document.getElementById("address_confirm").value;

            // Validate dữ liệu
            if (!name_confirm || !phone_confirm || !address_confirm) {
                showerror("Vui lòng điền đầy đủ thông tin!");
                return;
            }
            if (name_confirm.length < 3) {
                showerror("Tên phải có ít nhất 3 ký tự!");
                return;
            }
            if (!/^[0-9]{10}$/.test(phone_confirm)) {
                showerror("Số điện thoại không hợp lệ!");
                return;
            }

            $(".modal").style.display = "none";
            processOrder_gh(checkboxes);
            showsuccess("Đặt mua thành công !!!");
            setTimeout(() => {
                confirm_Address(name_confirm, phone_confirm, address_confirm);
                window.location.href = window.location.href;
            }, 2000);
        });
    }

}).catch(error => {
    console.error('Error loading data:', error);
});
