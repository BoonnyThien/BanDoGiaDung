// static-version/js/header.js
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

// Hàm hiển thị form đăng nhập
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

// Hàm hiển thị form đăng ký
function register_show() {
    const modal = $('.modal');
    const loginForm = $('.form-login');
    const registerForm = $('.form-register');
    const confirmForm = $('.form-confirm');
    if (modal && registerForm) {
        modal.style.display = 'flex';
        registerForm.style.display = 'block';
        if (loginForm) loginForm.style.display = 'none';
        if (confirmForm) confirmForm.style.display = 'none';
    }
}

// Hàm hiển thị form xác nhận
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

// Hàm khởi tạo sự kiện cho header
function initializeHeaderEvents() {
    // Tải dữ liệu JSON
    fetch('../web_giadung.json')
        .then(response => response.json())
        .then(jsonData => {
            const users = jsonData.tables.find(table => table.name === "tbl_user").data;
            const products = jsonData.tables.find(table => table.name === "tbl_product").data;

            // Kiểm tra trạng thái đăng nhập
            const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
            const userId = loggedInUser ? loggedInUser.user_id : "0";

            // Cập nhật giao diện đăng nhập/đăng ký/tài khoản
            const loginLink = $('.header__navbar-item--strong:nth-last-child(1)');
            const registerLink = $('.header__navbar-item--strong:nth-last-child(2)');
            if (loggedInUser) {
                // Nếu đã đăng nhập, hiển thị tên người dùng và nút đăng xuất
                registerLink.style.display = 'none';
                loginLink.innerHTML = `
                    <a href="taikhoan.html" class="header__navbar-itemlink">
                        <i class="header__navbar-icon fa-regular fa-user"></i>
                        ${loggedInUser.user_name}
                    </a>
                    <span class="header__navbar-item--strong logout">Đăng xuất</span>
                `;

                // Xử lý đăng xuất
                const logoutLink = $('.logout');
                if (logoutLink) {
                    logoutLink.addEventListener('click', function () {
                        localStorage.removeItem('loggedInUser');
                        showsuccess('Đăng xuất thành công!');
                        setTimeout(() => window.location.reload(), 2000);
                    });
                }
            } else {
                // Nếu chưa đăng nhập, hiển thị nút "Đăng ký" và "Đăng nhập"
                registerLink.addEventListener('click', register_show);
                loginLink.addEventListener('click', login_show);
            }

            // Hiển thị giỏ hàng
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const userCartItems = cartItems.filter(item => item.user_id === userId);
            const cartList = $('#cart-items');
            const cartNotice = $('#cart-count');
            const cartContainer = $('#cart-list');

            // Cập nhật số lượng sản phẩm trong giỏ hàng
            const cartCount = userCartItems.reduce((total, item) => total + parseInt(item.quantity), 0);
            if (cartNotice) {
                cartNotice.textContent = cartCount;
            }

            // Hiển thị sản phẩm trong giỏ hàng
            if (userCartItems.length > 0) {
                cartContainer.classList.remove('header__cart-list--no-cart');
                cartList.innerHTML = userCartItems.map(item => {
                    const product = products.find(p => p.product_id === item.product_id);
                    if (!product) return '';
                    return `
                        <li class="header__cart-item" data-product-id="${item.product_id}">
                            <img src="../assets/img/${product.main_image}" alt="" class="header__cart-img">
                            <div class="header__cart-item-info">
                                <div class="header__cart-item-head">
                                    <h5 class="header__cart-item-name">${product.product_name}</h5>
                                    <div class="header__cart-item-price-wrap">
                                        <span class="header__cart-item-price">${product.new_price} <sup>đ</sup></span>
                                        <span class="header__cart-item-multiply">x</span>
                                        <span class="header__cart-item-qnt">${item.quantity}</span>
                                    </div>
                                </div>
                                <div class="header__cart-item-body">
                                    <span class="header__cart-item-description">Phân loại: Đồ gia dụng</span>
                                    <span class="header__cart-item-remove">Xóa</span>
                                </div>
                            </div>
                        </li>
                    `;
                }).join('');
            } else {
                cartContainer.classList.add('header__cart-list--no-cart');
            }

            // Xử lý xóa sản phẩm trong giỏ hàng (trong header)
            $$('.header__cart-item-remove').forEach(removeBtn => {
                removeBtn.addEventListener('click', function () {
                    const cartItem = this.closest('.header__cart-item');
                    const productId = cartItem.getAttribute('data-product-id');
                    const updatedCart = cartItems.filter(item => !(item.product_id === productId && item.user_id === userId));
                    localStorage.setItem('cart', JSON.stringify(updatedCart));
                    showsuccess('Xóa sản phẩm thành công!');
                    setTimeout(() => window.location.reload(), 2000);
                });
            });

            // Xử lý tìm kiếm
            const searchInput = $('.header__search-input');
            const searchBtn = $('.header__search-btn');
            const searchHistoryList = $('#search-history-list');
            let searchHistory = JSON.parse(localStorage.getItem('searchHistory')) || [];

            // Hiển thị lịch sử tìm kiếm
            if (searchHistory.length > 0) {
                searchHistoryList.innerHTML = searchHistory.map(item => `
                    <li class="header__search-history-item">
                        <a href="index.html?query=${encodeURIComponent(item)}">${item}</a>
                    </li>
                `).join('');
            }

            // Xử lý sự kiện tìm kiếm
            if (searchBtn && searchInput) {
                searchBtn.addEventListener('click', function () {
                    const query = searchInput.value.trim();
                    if (query) {
                        // Cập nhật lịch sử tìm kiếm
                        if (!searchHistory.includes(query)) {
                            searchHistory.unshift(query);
                            if (searchHistory.length > 5) searchHistory.pop(); // Giới hạn 5 mục
                            localStorage.setItem('searchHistory', JSON.stringify(searchHistory));
                        }
                        // Chuyển hướng đến trang tìm kiếm
                        window.location.href = `index.html?query=${encodeURIComponent(query)}`;
                    }
                });

                // Hiển thị lịch sử tìm kiếm khi focus vào input
                searchInput.addEventListener('focus', function () {
                    if (searchHistory.length > 0) {
                        $('.header__search-history').style.display = 'block';
                    }
                });

                // Ẩn lịch sử tìm kiếm khi click ra ngoài
                document.addEventListener('click', function (e) {
                    if (!searchInput.contains(e.target) && !$('.header__search-history').contains(e.target)) {
                        $('.header__search-history').style.display = 'none';
                    }
                });
            }

            // Xử lý form đăng ký
            const registerForm = $('.form-register');
            if (registerForm) {
                registerForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const name = registerForm.querySelector('input[name="register_name"]').value;
                    const phone = registerForm.querySelector('input[name="register_phone"]').value;
                    const email = registerForm.querySelector('input[name="register_email"]').value;
                    const address = registerForm.querySelector('input[name="register_address"]').value;
                    const account = registerForm.querySelector('input[name="register_account"]').value;
                    const password = registerForm.querySelector('input[name="register_password"]').value;
                    const confirmPassword = registerForm.querySelector('input[name="register_confirm_password"]').value;

                    // Validate dữ liệu
                    if (!name || !phone || !email || !address || !account || !password || !confirmPassword) {
                        showerror('Vui lòng điền đầy đủ thông tin!');
                        return;
                    }
                    if (name.length < 3) {
                        showerror('Tên phải có ít nhất 3 ký tự!');
                        return;
                    }
                    if (!/^[0-9]{10}$/.test(phone)) {
                        showerror('Số điện thoại không hợp lệ!');
                        return;
                    }
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        showerror('Email không hợp lệ!');
                        return;
                    }
                    if (account.length < 3) {
                        showerror('Tên đăng nhập phải có ít nhất 3 ký tự!');
                        return;
                    }
                    if (password.length < 6) {
                        showerror('Mật khẩu phải có ít nhất 6 ký tự!');
                        return;
                    }
                    if (password !== confirmPassword) {
                        showerror('Mật khẩu xác nhận không khớp!');
                        return;
                    }
                    if (users.some(u => u.user_account === account)) {
                        showerror('Tên đăng nhập đã tồn tại!');
                        return;
                    }

                    // Thêm người dùng mới vào localStorage
                    const newUser = {
                        user_id: (users.length + 1).toString(),
                        user_name: name,
                        user_phone: phone,
                        user_email: email,
                        user_address: address,
                        user_account: account,
                        user_pass: password,
                        user_picture: 'user.png'
                    };
                    users.push(newUser);
                    localStorage.setItem('users', JSON.stringify(users)); // Lưu vào localStorage (giả lập)
                    localStorage.setItem('loggedInUser', JSON.stringify(newUser));
                    showsuccess('Đăng ký thành công!');
                    setTimeout(() => window.location.reload(), 2000);
                });
            }

            // Xử lý form đăng nhập
            const loginForm = $('.form-login');
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const account = loginForm.querySelector('input[name="login_account"]').value;
                    const password = loginForm.querySelector('input[name="login_password"]').value;
                    const user = users.find(u => u.user_account === account && u.user_pass === password);

                    if (user) {
                        localStorage.setItem('loggedInUser', JSON.stringify(user));
                        showsuccess('Đăng nhập thành công!');
                        setTimeout(() => window.location.reload(), 2000);
                    } else {
                        showerror('Tài khoản hoặc mật khẩu không đúng!');
                    }
                });
            }

            // Xử lý chuyển đổi giữa form đăng nhập và đăng ký
            $$('.auth-form__switch-btn').forEach(switchBtn => {
                switchBtn.addEventListener('click', function () {
                    if (this.parentElement.parentElement.classList.contains('form-login')) {
                        register_show();
                    } else {
                        login_show();
                    }
                });
            });

            // Xử lý nút "Trở lại" trong modal
            $$('.auth-form__controls-back').forEach(backBtn => {
                backBtn.addEventListener('click', function () {
                    $('.modal').style.display = 'none';
                });
            });

        })
        .catch(error => console.error('Error loading data:', error));
}

// Khởi tạo sự kiện khi DOM được tải
document.addEventListener('DOMContentLoaded', initializeHeaderEvents);