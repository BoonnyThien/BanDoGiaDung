// static-version/js/taikhoan.js
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

// Tải header, footer và dữ liệu JSON
Promise.all([
    fetch('../static-version/components/header.html').then(response => response.text()),
    fetch('../static-version/components/footer.html').then(response => response.text()),
    fetch('../web_giadung.json').then(response => response.json())
]).then(([headerContent, footerContent, jsonData]) => {
    $('#header').innerHTML = headerContent;
    $('#footer').innerHTML = footerContent;

    // Lấy dữ liệu từ JSON
    const products = jsonData.tables.find(table => table.name === "tbl_product").data;

    // Kiểm tra trạng thái đăng nhập
    const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
    const userId = loggedInUser ? loggedInUser.user_id : "0";

    if (!loggedInUser) {
        // Nếu chưa đăng nhập, chuyển hướng về trang chủ
        window.location.href = 'index.html';
        return;
    }

    // Hiển thị thông tin tài khoản
    const accountInfo = $('#account-info');
    if (accountInfo) {
        accountInfo.innerHTML = `
            <div class="account_wrap-child">
                <p>Tên: </p>
                <input id="account_name" type="text" value="${loggedInUser.user_name}">
            </div>
            <div class="account_wrap-child">
                <p>Phone: </p>
                <input id="account_phone" type="tel" value="${loggedInUser.user_phone}">
            </div>
            <div class="account_wrap-child">
                <p>Email: </p>
                <input id="account_email" type="email" value="${loggedInUser.user_email}">
            </div>
            <div class="account_wrap-child">
                <p>Address: </p>
                <input id="account_address" type="text" value="${loggedInUser.user_address || ''}">
            </div>
            <div class="account_wrap-child">
                <p>Tên đăng nhập: </p>
                <span>${loggedInUser.user_account}</span>
            </div>
            <div class="account_wrap-child">
                <p>Mật khẩu: </p>
                <input type="password" value="${loggedInUser.user_pass}" class="input_disable">
            </div>
            <div class="account_wrap-child">
                <button class="btn btn--primary btn_save">Xác nhận thay đổi</button>
            </div>
        `;
    }

    // Hiển thị ảnh người dùng
    const userPicture = $('#user-picture');
    if (userPicture && loggedInUser.user_picture) {
        userPicture.src = `../assets/img/user/${loggedInUser.user_picture}`;
    }

    // Hiển thị danh sách đơn hàng
    const orders = JSON.parse(localStorage.getItem('orders')) || [];
    const userOrders = orders.filter(order => order.user_id === userId);
    const orderList = $('#order-list');
    const orderMap = new Map();

    // Nhóm đơn hàng theo transaction_code
    userOrders.forEach(order => {
        if (!orderMap.has(order.transaction_code)) {
            orderMap.set(order.transaction_code, []);
        }
        orderMap.get(order.transaction_code).push(order);
    });

    // Hiển thị đơn hàng
    orderMap.forEach((orderItems, transactionCode) => {
        let totalPrice = 0;
        const orderHtml = orderItems.map(item => {
            const product = products.find(p => p.product_id === item.product_id);
            if (!product) return '';
            totalPrice += item.total_price;
            return `
                <div class="cart_product-wrap">
                    <div class="cart_product-select">
                        <div class="img_wrap">
                            <img src="../assets/img/${product.main_image}" alt="san pham">
                        </div>
                    </div>
                    <div class="cart_product-infor-wrap">
                        <div class="cart-infor">
                            <span class="cart-infor-name">${product.product_name}</span>
                            <div class="cartt-infor-price">
                                <span class="cart-infor-price-old" style="font-size: 1.8rem; color: #aaa; text-decoration: line-through;">
                                    ${product.old_price}<sup>đ</sup>
                                </span>
                                <span class="cart-infor-price-new" style="font-size: 2.2rem; color: var(--primary-color);">
                                    ${product.new_price}<sup>đ</sup>
                                </span>
                            </div>
                        </div>
                        <div class="cart_product-infor-number">
                            <p style="display: block; width: 100%; text-align: right;">Số lượng: ${item.quantity}</p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        if (orderList) {
            orderList.innerHTML += `
                <div class="order_content" data-order-code="${transactionCode}">
                    <div class="order_content-title">
                        <h1>Mã đơn hàng: ${transactionCode}</h1>
                        <p>Trạng thái: <span>Chờ xác nhận</span></p>
                    </div>
                    <div class="order_content-main">
                        ${orderHtml}
                        <div class="cart_totalPrice">
                            <div class="btn btn--primary deleteOder" style="margin-right: 20px; font-size: 1.8rem;">Hủy đơn hàng</div>
                            Thành tiền: <p>${totalPrice}<sup>đ</sup></p>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    // Ẩn/hiện các tab
    const listTK = $$(".category-item");
    const tkInfor = $(".account_wrap");
    const orderInfor = $(".order_wrap");
    const supportInfor = $(".support_wrap");

    if (tkInfor) {
        tkInfor.classList.add("display_block");
    }

    if (listTK) {
        listTK.forEach((listItem, index) => {
            listItem.addEventListener("click", function () {
                listTK.forEach((i) => {
                    i.classList.remove("category-item--active");
                });
                listItem.classList.add("category-item--active");

                tkInfor.classList.remove("display_block");
                orderInfor.classList.remove("display_block");
                supportInfor.classList.remove("display_block");

                if (index === 0) {
                    tkInfor.classList.add("display_block");
                } else if (index === 1) {
                    orderInfor.classList.add("display_block");
                } else if (index === 2) {
                    supportInfor.classList.add("display_block");
                    renderSupportForm(supportInfor.querySelector('#support-form'));
                }
            });
        });
    }

    // Thay đổi ảnh người dùng
    $(".btn_change-img").addEventListener("click", function () {
        document.getElementById("fileInput").click();
    });

    document.getElementById("fileInput").addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                userPicture.src = e.target.result;
                loggedInUser.user_picture = file.name; // Lưu tên file vào loggedInUser (giả lập)
                localStorage.setItem('loggedInUser', JSON.stringify(loggedInUser));
            };
            reader.readAsDataURL(file);
        }
    });

    // Cập nhật thông tin tài khoản
    function update_account(name, phone, email, address) {
        loggedInUser.user_name = name;
        loggedInUser.user_phone = phone;
        loggedInUser.user_email = email;
        loggedInUser.user_address = address;
        localStorage.setItem('loggedInUser', JSON.stringify(loggedInUser));
    }

    $(".btn_save").addEventListener("click", function () {
        const fileInput = document.getElementById("fileInput");
        const file = fileInput.files[0];
        const name = document.getElementById("account_name").value;
        const phone = document.getElementById("account_phone").value;
        const email = document.getElementById("account_email").value;
        const address = document.getElementById("account_address").value;

        // Validate dữ liệu
        if (!name || !phone || !email) {
            showerror("Vui lòng điền đầy đủ thông tin!");
            return;
        }
        if (name.length < 3) {
            showerror("Tên phải có ít nhất 3 ký tự!");
            return;
        }
        if (!/^[0-9]{10}$/.test(phone)) {
            showerror("Số điện thoại không hợp lệ!");
            return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showerror("Email không hợp lệ!");
            return;
        }
        if (!file) {
            showerror("Vui lòng chọn một ảnh trước khi lưu!");
            return;
        }

        showsuccess("Sửa thông tin thành công!");
        update_account(name, phone, email, address);
    });

    // Xóa đơn hàng
    function deleteOder(order_code) {
        const updatedOrders = orders.filter(order => order.transaction_code !== order_code);
        localStorage.setItem('orders', JSON.stringify(updatedOrders));
    }

    $$('.deleteOder').forEach((btnDelete) => {
        btnDelete.addEventListener('click', function () {
            const order_wrap = this.closest('.order_content');
            const order_code = order_wrap.getAttribute('data-order-code');

            deleteOder(order_code);
            showsuccess("Xóa đơn hàng thành công!");
            order_wrap.classList.add('display_none');
        });
    });

}).catch(error => {
    console.error('Error loading data:', error);
});

// Tải hàm renderSupportForm từ trogiup.js
document.addEventListener('DOMContentLoaded', () => {
    const script = document.createElement('script');
    script.src = './js/trogiup.js';
    script.onload = () => {
        if (typeof renderSupportForm === 'function') {
            const supportTab = $$('.category-item')[2];
            if (supportTab && supportTab.classList.contains('category-item--active')) {
                renderSupportForm($('.support-form'));
            }
        }
    };
    document.body.appendChild(script);
});