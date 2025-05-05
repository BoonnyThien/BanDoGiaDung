// static-version/js/sosanh.js
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
    fetch('./static-version/components/header.html').then(response => response.text()),
    fetch('./static-version/components/footer.html').then(response => response.text()),
    fetch('./web_giadung.json').then(response => response.json())
]).then(([headerContent, footerContent, jsonData]) => {
    $('#header').innerHTML = headerContent;
    $('#footer').innerHTML = footerContent;

    // Lấy dữ liệu từ JSON
    const products = jsonData.tables.find(table => table.name === "tbl_product").data;
    const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;

    // Khởi tạo danh sách so sánh từ localStorage
    let compareProducts = JSON.parse(localStorage.getItem('compare_products')) || Array(4).fill(null);
    if (compareProducts.length !== 4) {
        compareProducts = Array(4).fill(null); // Đảm bảo mảng có đúng 4 phần tử
        localStorage.setItem('compare_products', JSON.stringify(compareProducts));
    }

    // Lấy chi tiết sản phẩm so sánh
    let compareProductDetails = compareProducts.map(id => {
        if (id !== null) {
            const product = products.find(p => p.product_id === id);
            if (product) {
                const brand = brands.find(b => b.brand_id === product.brand_id);
                return { ...product, brand_name: brand ? brand.brand_name : 'Không xác định' };
            }
        }
        return null;
    });

    // Xử lý tìm kiếm
    let searchResults = [];
    let hasSearched = false;
    const limit = 30;
    let page = 1;
    let offset = (page - 1) * limit;

    // Hiển thị bảng so sánh và kết quả tìm kiếm
    const compareContent = $('#compare-content');
    function renderCompareContent() {
        const fields = {
            'Hình ảnh': ['main_image', 'image'],
            'Thương hiệu': 'brand_name',
            'Tên sản phẩm': 'product_name',
            'Giá cũ': ['old_price', 'price'],
            'Giá mới': ['new_price', 'price'],
            'Xuất xứ': 'origin',
            'Bảo hành': 'warranty_period',
            'Loại bảo hành': 'warranty_option',
            'Chất liệu': 'material'
        };

        let compareTableHtml = `
            <h5>SO SÁNH NHANH ✨</h5>
            <form id="compare-form">
                <input type="submit" class="xoa" value="Xóa sản phẩm đã chọn">
                <table class="compare-table">
                    <tr>
                        <th></th>
                        ${compareProductDetails.map((product, index) => `
                            <td class="${product ? 'has-product' : 'empty-cell'}">
                                ${product ? `<input type="checkbox" name="remove_ids[]" value="${product.product_id}">` : ''}
                            </td>
                        `).join('')}
                    </tr>
        `;

        for (const [label, field] of Object.entries(fields)) {
            compareTableHtml += `
                <tr>
                    <th>${label}</th>
                    ${compareProductDetails.map(product => `
                        <td class="${product ? 'has-product product-cell' : 'empty-cell'}">
                            ${product ? `
                                <a href="chitiet.html?product_id=${product.product_id}" target="_blank" class="product-link">
                                    ${Array.isArray(field) ? (
                                        field[1] === 'image' ? `<img src="./assets/img/${product[field[0]]}" alt="" style="width:100px;height:100px;object-fit:cover;">` :
                                        field[1] === 'price' ? `<span class="pri">${new Intl.NumberFormat('vi-VN').format(product[field[0]])}đ</span>` :
                                        product[field[0]]
                                    ) : product[field]}
                                </a>
                            ` : ''}
                        </td>
                    `).join('')}
                </tr>
            `;
        }

        compareTableHtml += `
                </table>
            </form>
            <form id="search-form" class="search-form">
                <div class="sosanh">
                    <div class="ssname">So sánh sản phẩm 🔎</div>
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm">
                    <input type="submit" value="Tìm kiếm">
                </div>
            </form>
            <h5>LỰA CHỌN SẢN PHẨM SO SÁNH ✨</h5>
            <div class="search-results">
        `;

        if (!hasSearched) {
            compareTableHtml += Array(5).fill().map(() => `
                <div class="card empty-card">
                    <div class="sanpham thuonghieu">Chưa có sản phẩm</div>
                    <div class="placeholder-image"></div>
                    <div class="sanpham name">Vui lòng tìm kiếm sản phẩm</div>
                    <nav class="price">
                        <div class="sanpham old_price pri">---</div>
                        <div class="sanpham percent">---%</div>
                    </nav>
                    <div class="sanpham new_price pri">---</div>
                </div>
            `).join('');
        } else {
            compareTableHtml += searchResults.map(product => {
                const discount = product.old_price > 0 ? ((product.old_price - product.new_price) / product.old_price * 100).toFixed(1) : 0;
                return `
                    <div class="card" onclick="addToCompare('${product.product_id}')">
                        <div class="sanpham thuonghieu">${product.brand_name}</div>
                        <img src="./assets/img/${product.main_image}" alt="">
                        <div class="sanpham name">${product.product_name}</div>
                        <nav class="price">
                            <div class="sanpham old_price pri">${new Intl.NumberFormat('vi-VN').format(product.old_price)}đ</div>
                            <div class="sanpham percent">${discount}%</div>
                        </nav>
                        <div class="sanpham new_price pri">${new Intl.NumberFormat('vi-VN').format(product.new_price)}đ</div>
                    </div>
                `;
            }).join('');
        }

        compareTableHtml += `</div>`;
        compareContent.innerHTML = compareTableHtml;

        // Xử lý sự kiện xóa sản phẩm so sánh
        const compareForm = $('#compare-form');
        if (compareForm) {
            compareForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const checkboxes = $$('input[name="remove_ids[]"]:checked');
                checkboxes.forEach(checkbox => {
                    const productId = checkbox.value;
                    const index = compareProducts.indexOf(productId);
                    if (index !== -1) {
                        compareProducts[index] = null;
                    }
                });
                localStorage.setItem('compare_products', JSON.stringify(compareProducts));
                compareProductDetails = compareProducts.map(id => {
                    if (id !== null) {
                        const product = products.find(p => p.product_id === id);
                        if (product) {
                            const brand = brands.find(b => b.brand_id === product.brand_id);
                            return { ...product, brand_name: brand ? brand.brand_name : 'Không xác định' };
                        }
                    }
                    return null;
                });
                showsuccess('Xóa sản phẩm so sánh thành công!');
                renderCompareContent();
            });
        }

        // Xử lý sự kiện tìm kiếm
        const searchForm = $('#search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const searchTerm = searchForm.querySelector('input[name="search"]').value.trim();
                if (searchTerm) {
                    hasSearched = true;
                    searchResults = products.filter(product => {
                        const brand = brands.find(b => b.brand_id === product.brand_id);
                        const brandName = brand ? brand.brand_name : '';
                        return (
                            product.product_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            brandName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            product.old_price.toString().includes(searchTerm) ||
                            product.new_price.toString().includes(searchTerm) ||
                            product.origin.toLowerCase().includes(searchTerm.toLowerCase()) ||
                            (product.material && product.material.toLowerCase().includes(searchTerm.toLowerCase()))
                        );
                    }).slice(offset, offset + limit);
                    renderCompareContent();
                }
            });
        }
    }

    // Hàm thêm sản phẩm vào danh sách so sánh
    window.addToCompare = function (productId) {
        const emptySlot = compareProducts.indexOf(null);
        if (emptySlot !== -1) {
            if (!compareProducts.includes(productId)) {
                compareProducts[emptySlot] = productId;
                localStorage.setItem('compare_products', JSON.stringify(compareProducts));
                compareProductDetails = compareProducts.map(id => {
                    if (id !== null) {
                        const product = products.find(p => p.product_id === id);
                        if (product) {
                            const brand = brands.find(b => b.brand_id === product.brand_id);
                            return { ...product, brand_name: brand ? brand.brand_name : 'Không xác định' };
                        }
                    }
                    return null;
                });
                showsuccess('Thêm sản phẩm vào so sánh thành công!');
                renderCompareContent();
            } else {
                showerror('Sản phẩm đã có trong danh sách so sánh!');
            }
        } else {
            showerror('Bảng so sánh đã đầy!');
        }
    };

    // Hiển thị nội dung ban đầu
    renderCompareContent();

}).catch(error => {
    console.error('Error loading data:', error);
});