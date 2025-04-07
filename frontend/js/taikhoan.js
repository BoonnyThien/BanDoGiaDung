function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

const listTK = $$(".category-item");
const tkInfor = $(".account_wrap");
const orderInfor = $(".order_wrap");
if (tkInfor) {
    tkInfor.classList.add("display_block");
}

//_________________________________________________________________Hiện bảng đã chọn và ẩn các bảng khác___________________________________________________________________//

if (listTK) {
    listTK.forEach((listItem, index) => {
        listItem.addEventListener("click", function () {
            listTK.forEach((i) => {
                i.classList.remove("category-item--active");
            });
            listItem.classList.add("category-item--active");
            if (index == 0) {
                tkInfor.classList.add("display_block");
                orderInfor.classList.remove("display_block");
            } else if (index == 1) {
                orderInfor.classList.add("display_block");
                tkInfor.classList.remove("display_block");
            }
        });
    });
}

//______________________________________________________________THay đổi ảnh và thay đổi trực tiếp qua input hiden_____________________________________________________//

$(".btn_change-img").addEventListener("click", function () {
    document.getElementById("fileInput").click();
});

document.getElementById("fileInput").addEventListener("change", function () {
    // Lấy file đã chọn
    const file = this.files[0];
    if (file) {
        // Hiển thị ảnh đã chọn bằng cách đọc file
        const reader = new FileReader();
        reader.onload = function (e) {
            // Đổi src của thẻ img để hiển thị ảnh đã chọn
            $(".account_img img").src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

//_________________________________________________________________Button Save (gọi hàm sửa thông tin tài khoản) ______________________________________________________________________//

//Hàm Cập nhập thông tin tài khoản
function update_account(file, name, phone, email, address) {
    const formData = new FormData();
    formData.append("user_picture", file);
    formData.append("name", name);
    formData.append("phone", phone);
    formData.append("email", email);
    formData.append("address", address);
    formData.append("action", "update_account");

    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch((error) => console.error("Lỗi khi gửi dữ liệu:", error));
}

$(".btn_save").addEventListener("click", function () {
    const fileInput = document.getElementById("fileInput");
    const file = fileInput.files[0];
    const name = document.getElementById("account_name").value;
    const phone = document.getElementById("account_phone").value;
    const email = document.getElementById("account_email").value;
    const address = document.getElementById("account_address").value;

    if (!file) {
        showerror("Vui lòng chọn một ảnh trước khi lưu !!!");
        return;
    }
    showsuccess("Sửa thông tin thành công!!!");
    update_account(file, name, phone, email, address);
});




//________________________________________________________________________Xoa don hang_____________________________________________________________________//

function deleteOder(order_code){
    const formData = new FormData();
    formData.append("code", order_code);
    formData.append("action", "delete_order");
    
    fetch('../database/main.php', {
        method: 'POST',
        body: formData,
    }).catch((error) => console.error("Lỗi khi gửi dữ liệu:", error));
}

$$('.deleteOder').forEach((btnDelete => {
    btnDelete.addEventListener('click', function(){
        const order_wrap = this.closest('.order_content')
        const order_code = order_wrap.getAttribute('data-order-code')

        deleteOder(order_code)
        showsuccess("Xóa đơn hàng thành công!!!")
        order_wrap.classList.add('display_none')
    })
}));