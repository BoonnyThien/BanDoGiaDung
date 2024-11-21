function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// ___________________________________________________________________________Tăng giảm input số lượng_____________________________________________________________________//

const decrease = $(".decrease");
const increase = $(".increase");
const inputElement = $(".product_detail-buy-updown");

decrease.addEventListener("click", function () {
    let value = parseInt(inputElement.value);
    if (value > 1) inputElement.value = value - 1;
});

increase.addEventListener("click", function () {
    let value = parseInt(inputElement.value);
    inputElement.value = value + 1;
});




// ______________________________________________________________________________Thay đổi ảnh chính hiển thị_______________________________________________________________________//

const imgMain = $(".img_main");
const imgExtras = $$(".img_sub img");

imgExtras.forEach((imgItem) => {
    imgItem.addEventListener("mouseenter", () => {
        imgMain.src = imgItem.src;
        imgExtras.forEach((item) =>
            item.classList.toggle("border_active", item === imgItem)
        );
    });
});




// ________________________________________________________________________Nút thêm sẩn phẩm vào giỏ hàng_____________________________________________________________________________________//

function updateQuantity(productId, newQuantity) {
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("quantity", newQuantity);
    formData.append("action", "cart_update");

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch(error => console.error('Lỗi khi gửi dữ liệu:', error));
}

const btnAddCart = $(".product_detail-btncart");
if (btnAddCart) {
    btnAddCart.addEventListener("click", function () {
        const quantity = inputElement.value;
        const sp_id = inputElement.getAttribute("data-product_id");
        updateQuantity(sp_id, quantity);
        showsuccess("Thêm sản phẩm thành công !!!");
        setTimeout(() => {
            window.location.href = "?product_id=" + sp_id;
        }, 2000);
    });
}




// ______________________________________________________________________________Nút mua sản phẩm_______________________________________________________________________________//

const btnAddOrder = $(".product_detail-btnbuy");
if (btnAddOrder) {
    btnAddOrder.addEventListener("click", function () {
        const user_id = $(".product_detail").getAttribute("data-user-id");
        if (parseInt(user_id) != 0) confirm_show();
        else login_show();
    });
}




// ___________________________________________________________________________Form xác nhận ___________________________________________________________________________//

// Hàm cập nhập Địa chỉ
function confirm_Address(name, phone, address){
    const formData = new FormData();
    formData.append('name', name);
    formData.append('phone', phone); 
    formData.append('address', address); 
    formData.append('action', 'update_address');

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch(error => console.error('Lỗi khi gửi dữ liệu:', error));
}

// Hàm thêm sản phẩm vào đơn hang
function addProduct_dh(orderCode, productId, numberBuy, onePrice) {
    const formData = new FormData();
    formData.append('orderCode', orderCode);
    formData.append('productId', productId); 
    formData.append('numberBuy', numberBuy); 
    formData.append('onePrice', onePrice); 
    formData.append('action', 'order_add');

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch(error => console.error('Lỗi khi gửi dữ liệu:', error));
}

function processOrder_ct() {
    const randomNumber = Math.floor(Math.random() * 1000000);
    const orderCode = "DH" + randomNumber.toString().padStart(6, "0");
    const sp_id = inputElement.getAttribute("data-product_id");
    const quantity = inputElement.value;
    const onePrice = parseInt(
        $(".product_detail-price-new").getAttribute("data-price")
    );

    addProduct_dh(orderCode, sp_id, quantity, onePrice);
}

//Xử lý nút xác nhận của form
const confirmBtnAdress = $('.btn_confirm-address')
if(confirmBtnAdress){
    confirmBtnAdress.addEventListener('click',function(){
        const name_confirm = document.getElementById('name_confirm').value
        const phone_confirm = document.getElementById('phone_confirm').value
        const address_confirm = document.getElementById('address_confirm').value
        $(".modal").style.display = "none";

        processOrder_ct();
        showsuccess('Đặt mua thành công !!!')
        setTimeout(() => {
            confirm_Address(name_confirm, phone_confirm, address_confirm);
            window.location.href = window.location.href;
        },2000)
    })
}




// ____________________________________________________________Next product, before product (sản phẩm liên quan)____________________________________________________//

const positionProduct = $$('.grid_no_wrap .product');
const nextProduct = $('.next_product')
const beforeProduct = $('.before_product')
const containerProduct = $('.grid_no_wrap')
const pageDetail = $('.product_detail')
let currentIndex = 0;
let maxIndex = positionProduct.length - 5;
let styleleft = 20;
if (pageDetail) {
    if (pageDetail.getAttribute('data-page') === 'detail') {
        styleleft = 16.667;
        maxIndex = positionProduct.length - 6;
    }
}
if (nextProduct && beforeProduct) {
    nextProduct.addEventListener('click', function () {
        if (currentIndex < maxIndex) {
            currentIndex++;
            containerProduct.style.left = "-" + currentIndex * styleleft + "%";
        }
    });

    beforeProduct.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            containerProduct.style.left = "-" + currentIndex * styleleft + "%";
        }
    });
}