function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// ________________________________________________________________________________Checkbox sản phẩm_____________________________________________________________________________________//

const selectAllCheckbox = $('.cart_select-all input[type="checkbox"]');
const checkboxes = $$('.cart_product-select input[type="checkbox"]');
const numberCheck = $(".cart_select-all p span");
const payElement = $(".pay_element");
const totalPriceElement = $(".totalPrice");
const discountPriceElement = $(".discoundPrice");

let i = 0;
// Hàm đếm số sản phẩm đang chọn
function updateCheckedCount() {
    i = 0;
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            ++i;
        }
    });
    numberCheck.innerText = i;
}
// Cập nhập lại tổng tiền và số lượng sản phẩm chọn khi chọn thêm sản phẩm hay bỏ chọn sản phẩm
checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        updateCheckedCount();
        updateTotalPrice();
        // Kiểm tra nếu tất cả các checkbox đều được chọn thì check "select all"
        if (Array.from(checkboxes).every((chk) => chk.checked)) {
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.checked = false;
        }
    });
});
// Cập nhập lại tổng tiền và số lượng sản phẩm chọn khi nhấn chọn(bỏ chọn) tất cả sản phẩm
selectAllCheckbox.addEventListener("change", function () {
    if (this.checked) {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = true;
        });
    } else {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
        });
    }
    updateCheckedCount();
    updateTotalPrice();
});

// ________________________________________________________________________Hàm tính tổng tiền của các sản phẩm được chọn____________________________________________________________//

function updateTotalPrice() {
    let payPrice = 0;
    let totalPrice = 0;
    let discountPrice = 0;
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const product = checkbox.closest(".cart_product-wrap");
            const quantity = product.querySelector(
                ".product_detail-buy-updown"
            ).value; // Lấy số lượng

            const new_price = product.getAttribute("data-product-new-price"); // Lấy giá
            payPrice += quantity * new_price;

            const old_price = product.getAttribute("data-product-old-price"); // Lấy giá
            totalPrice += quantity * old_price;

            discountPrice = totalPrice - payPrice;
        }
    });
    payElement.innerText = new Intl.NumberFormat("vi-VN").format(payPrice) + " ₫"; // Cập nhật tổng tiền
    totalPriceElement.innerText =
        new Intl.NumberFormat("vi-VN").format(totalPrice) + " ₫";
    discountPriceElement.innerText =
        new Intl.NumberFormat("vi-VN").format(discountPrice) + " ₫";
}

// ____________________________________________________________________________THêm, bớt số lượng và cập nhập lên CSDL_____________________________________________________________-//

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

$$(".decrease").forEach(function (decrease) {
    decrease.addEventListener("click", function () {
        const inputElement = this.closest(".product_detail-number").querySelector(
            ".product_detail-buy-updown"
        );
        const productId = this.closest(".cart_product-wrap").getAttribute("data-product-id");
        let value = parseInt(inputElement.value);
        if (value > 1) inputElement.value = value - 1;
        updateQuantity(productId, inputElement.value);
        updateTotalPrice();
    });
});

$$(".increase").forEach(function (increase) {
    increase.addEventListener("click", function () {
        const inputElement = this.closest(".product_detail-number").querySelector(
            ".product_detail-buy-updown"
        );
        const productId = this.closest(".cart_product-wrap").getAttribute("data-product-id");
        let value = parseInt(inputElement.value);
        inputElement.value = value + 1;
        updateQuantity(productId, inputElement.value);
        updateTotalPrice();
    });
});

// ____________________________________________________________________________________Xóa sản phẩm trong giả hàng và cập nhập lên CSDL_____________________________________________________________________-//

// Ham Xoa sp giỏ hang trên csdl
function deleteProduct_gh(productId) {
    const formData = new FormData();
    formData.append("productId", productId);
    formData.append("action", "cart_delete");

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch((error) => console.error("Lỗi khi gửi dữ liệu:", error));
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
        cart_notifis.forEach(function (cart_notifi) {
            if (cart_notifi.getAttribute("data-product-id") == id_xoa) {
                cart_notifi.classList.add("display_none");
            }
        });

        //Bỏ chọn các checkbox
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
            selectAllCheckbox.checked = false;
            numberCheck.innerText = 0;
            updateTotalPrice();
        });
        
        //Gọi hàm xóa để gửi dữ liệu lên csdl
        deleteProduct_gh(id_xoa);
        showsuccess("Xóa sản phẩm thành công !!!");

        //Kiểm tra xem giỏ hàng còn sản phẩm không
        cartNotice.textContent = Math.max(0, parseInt(cartNotice.textContent) - 1);
        if (parseInt(cartNotice.textContent) == 0) {
            notifiProduct_list.classList.add("header__cart-list--no-cart");
            cartPosition.innerHTML = `
                <div style="width: 100%; height: 100%; background-color: white; padding: 10px; border-radius: 20px; text-align: center;">
                    <h1 style="width: 100%;">Không có sản phẩm</h1>
                    <img src="../assets/img/no_cart.png" alt="Không có sản phẩm" style="display: block; margin: 0 auto;">
                </div>`;
        }
    });
});

// ______________________________________________________________Định nghĩa hàm thêm các sản phẩm được check vào đơn hàng và xóa khỏi giỏ hàng____________________________________________________________//

//Thêm sản phẩm vào đơn hang//
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

function processOrder_gh(checkboxes) {
    const randomNumber = Math.floor(Math.random() * 1000000);
    const orderCode = "DH" + randomNumber.toString().padStart(6, "0");

    if (checkboxes) {
        checkboxes.forEach((checkbox) => {
            if (checkbox.checked) {
                const product = checkbox.closest(".cart_product-wrap");
                const onePrice = product.getAttribute("data-product-old-price");
                const product_id = product.getAttribute("data-product-id");
                const numberBuy = product.querySelector(
                    ".product_detail-buy-updown"
                ).value;

                // Gọi các hàm để thêm sản phẩm và xóa trong giỏ hàng
                addProduct_dh(orderCode, product_id, numberBuy, onePrice);
                deleteProduct_gh(product_id);
            }
        });
    }
}

// ___________________________________________________________________________________Btn Đặt hàng ___________________________________________________________________________________//

const btn_buy = $(".cart_confirm-btn");
if (btn_buy) {
    btn_buy.addEventListener("click", function () {
        const user_id = btn_buy.getAttribute("data-user-id");
        if (parseInt(numberCheck.textContent) != 0) {
            if (parseInt(user_id) != 0) {
                confirm_show();
            } else login_show();
        } else {
            alert("chưa chọn sản phẩm nào");
        }
    });
}

// ___________________________________________________________________________Form xác nhận ___________________________________________________________________________//

//Hàm cập nhập Địa chỉ
function confirm_Address(name, phone, address) {
    const formData = new FormData();
    formData.append("name", name);
    formData.append("phone", phone);
    formData.append("address", address);
    formData.append("action", "update_address");

    fetch("../database/main.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Lỗi mạng: " + response.statusText);
            }
            return response.text();
        })
        .then((result) => {
            console.log("Cập nhật địa chỉ thành công:", result);
        })
        .catch((error) => {
            console.error("Lỗi khi gửi dữ liệu:", error);
        });
}

const confirmBtnAdress = $(".btn_confirm-address");
if (confirmBtnAdress) {
    confirmBtnAdress.addEventListener("click", function () {
        const name_confirm = document.getElementById("name_confirm").value;
        const phone_confirm = document.getElementById("phone_confirm").value;
        const address_confirm = document.getElementById("address_confirm").value;
        $(".modal").style.display = "none";

        processOrder_gh(checkboxes);
        showsuccess("Đặt mua thành công !!!");
        setTimeout(() => {
            confirm_Address(name_confirm, phone_confirm, address_confirm);
            window.location.href = window.location.href;
        }, 2000);
    });
}
