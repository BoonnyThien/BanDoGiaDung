function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}



//___________________________________________________________________Thu nhỏ header khi đang lướt xuống______________________________________________________//
const header = $(".header");
let y = 0;
window.addEventListener("scroll", function () {
    let x = window.pageYOffset;
    if (x >= y) {
        header.classList.add("value_top");
    } else {
        header.classList.remove("value_top");
    }
    y = x;
});

const cart = $(".header__cart-icon");
if (cart) {
    cart.addEventListener("click", function () {
        window.location.href = "giohang.php";
    });
}




// ___________________________________________________Logic ẩn hiển các form đăng nhập, đăng kí, xác nhận thông tin__________________________________//

const elm_authors = $$(".header__navbar-item.header__navbar-item--strong");
const elm_login_show = $(".form-login");
const elm_register_show = $(".form-register");
const elm_confirm_show = $(".form-confirm");
const elm_modal = $(".modal");
const help_active = $(".help_active");
const elm_backs = $$(".auth-form__controls-back");
const elm_switch_btns = $$(".auth-form__switch-btn");

function fun_switch() {
    if (elm_register_show.style.display === "none") {
        elm_register_show.style.display = "block";
        elm_login_show.style.display = "none";
        elm_confirm_show.style.display = "none";
    } else {
        elm_login_show.style.display = "block";
        elm_register_show.style.display = "none";
        elm_confirm_show.style.display = "none";
    }
}

function form_hide() {
    elm_modal.style.display = "none";
    elm_register_show.style.display = "none";
    elm_login_show.style.display = "none";
    elm_confirm_show.style.display = "none";
}

function register_show() {
    elm_modal.style.display = "flex";
    elm_register_show.style.display = "block";
}

function login_show() {
    elm_modal.style.display = "flex";
    elm_login_show.style.display = "block";
}

function confirm_show() {
    elm_modal.style.display = "flex";
    elm_confirm_show.style.display = "block";
}

for (let elm_event of elm_authors) {
    if (elm_event.classList.contains("header__navbar-item--separate")) {
        elm_event.addEventListener("click", register_show);
    } else {
        elm_event.addEventListener("click", login_show);
    }
}

for (let elm_back of elm_backs) {
    elm_back.addEventListener("click", form_hide);
}

for (let elm_switch_btn of elm_switch_btns) {
    elm_switch_btn.addEventListener("click", fun_switch);
}




// ______________________________________________________________________________Đăng nhập________________________________________________________________________________//

// if (elm_login_show) {
//   elm_login_show.addEventListener("submit", function (event) {
//     event.preventDefault();
//   });
// }

const errorLogin = $(".error-message");
if (errorLogin) login_show();




// ________________________________________________________________________Đăng Ký___________________________________________________________________________________//

if (elm_register_show) {
    elm_register_show.addEventListener("submit", function (event) {
        const password = $('input[name="register_password"]').value;
        const confirmPassword = $('input[name="register_confirm_password"]').value;
        if (password !== confirmPassword) {
            alert("Mật khẩu xác nhận không khớp. Vui lòng kiểm tra lại!");
            event.preventDefault();
        } else {
            alert("Đăng ký thành công!");
        }
    });
}




//_________________________________________________________________________________________Tìm kiếm _____________________________________________________________//

//Hàm UPDATE searchHistory
function searchHistory_add (valueSearch){
    const formData = new FormData();
    formData.append('valueSearch', valueSearch);
    formData.append('action', 'update_history');

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi mạng: ' + response.statusText);
            }
            return response.text();
        })
        .then(result => {
            console.log('Cập nhật history thành công:', result);
        })
        .catch(error => {
            console.error('Lỗi khi gửi dữ liệu:', error);
        });
}

const btnSeaarch = $(".header__search-btn");
const inputSearch = $(".header__search-input");
if (btnSeaarch && inputSearch) {
    btnSeaarch.addEventListener("click", function () {
        let valueSearch = inputSearch.value;
        searchHistory_add(valueSearch);
        window.location.href = "index.php?search=" + valueSearch;
    });
}




// __________________________________________________________________________Xóa sản phẩm id trong giỏ hàng thông báo ___________________________________________________//

//Xoa sp trong giỏ hang//

function deleteProduct_gh(productId) {
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("action", "cart_delete");

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch(error => console.error('Lỗi khi gửi dữ liệu:', error));
}

const dlNotifiCarts = $$('.header__cart-item-remove')
const cartNotice = $('.header__cart-notice')
const cart_products = $$('.cart_product-wrap')

if (dlNotifiCarts.length > 0) {
    dlNotifiCarts.forEach(function (dlNotifiCart) {
        dlNotifiCart.addEventListener('click', function () {
            const notifiProduct_wrap = dlNotifiCart.closest('.header__cart-item')
            const notifiProduct_list = dlNotifiCart.closest('.header__cart-list')
            const id_product = dlNotifiCart.getAttribute('data-product-id')
            notifiProduct_wrap.classList.add('display_none')

            if (cart_products.length > 0) {
                cart_products.forEach(function (cart_product) {
                    if (cart_product.getAttribute('data-product-id') == id_product) {
                        cart_product.classList.add('display_none')
                    }
                })
            }
            cartNotice.textContent = Math.max(0, parseInt(cartNotice.textContent) - 1)
            if (parseInt(cartNotice.textContent) == 0) {
                notifiProduct_list.classList.add('header__cart-list--no-cart')
            }
            deleteProduct_gh(id_product)
            showsuccess('Xóa sản phẩm thành công!!!')
        })
    })
}