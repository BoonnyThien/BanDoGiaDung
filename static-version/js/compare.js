document.addEventListener('DOMContentLoaded', () => {
    // Lấy danh sách sản phẩm cần so sánh từ localStorage
    const compareList = JSON.parse(localStorage.getItem('compareList')) || [];
    
    // Lấy container chứa bảng so sánh
    const compareTable = document.querySelector('.compare__table');
    const compareEmpty = document.querySelector('.compare__empty');
    
    // Hiển thị trạng thái trống hoặc bảng so sánh
    if (compareList.length === 0) {
        compareTable.style.display = 'none';
        compareEmpty.style.display = 'block';
        return;
    }
    
    compareTable.style.display = 'block';
    compareEmpty.style.display = 'none';
    
    // Load thông tin sản phẩm từ file JSON
    fetch('/static-version/data/products.json')
        .then(response => response.json())
        .then(data => {
            const products = data.products;
            const compareProducts = products.filter(product => 
                compareList.includes(product.id)
            );
            
            // Hiển thị thông tin sản phẩm trong header
            const headerCells = document.querySelectorAll('.compare__cell--product');
            compareProducts.forEach((product, index) => {
                if (index < headerCells.length) {
                    const cell = headerCells[index];
                    cell.innerHTML = `
                        <button class="compare__remove" data-id="${product.id}">
                            <i class="fas fa-times"></i>
                        </button>
                        <img src="${product.image}" alt="${product.name}" class="compare__product-image">
                        <h3 class="compare__product-name">${product.name}</h3>
                        <div class="compare__product-price">${formatPrice(product.price)}</div>
                        <a href="/static-version/product-detail.html?id=${product.id}" class="btn btn--primary">
                            Xem chi tiết
                        </a>
                    `;
                }
            });
            
            // Hiển thị thông tin so sánh
            const compareBody = document.querySelector('.compare__body');
            compareBody.innerHTML = generateCompareRows(compareProducts);
            
            // Thêm event listener cho nút xóa sản phẩm
            document.querySelectorAll('.compare__remove').forEach(button => {
                button.addEventListener('click', (e) => {
                    const productId = e.currentTarget.dataset.id;
                    removeFromCompare(productId);
                });
            });
        })
        .catch(error => {
            console.error('Error loading products:', error);
            compareTable.innerHTML = '<p class="error">Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.</p>';
        });
});

// Hàm tạo các hàng so sánh
function generateCompareRows(products) {
    const sections = [
        {
            title: 'Thông tin cơ bản',
            features: [
                { key: 'Thương hiệu', field: 'brand' },
                { key: 'Danh mục', field: 'category' },
                { key: 'Bảo hành', field: 'warranty' }
            ]
        },
        {
            title: 'Thông số kỹ thuật',
            features: [
                { key: 'Kích thước', field: 'dimensions' },
                { key: 'Trọng lượng', field: 'weight' },
                { key: 'Chất liệu', field: 'material' }
            ]
        },
        {
            title: 'Tính năng',
            features: [
                { key: 'Màu sắc', field: 'color' },
                { key: 'Tính năng đặc biệt', field: 'specialFeatures' }
            ]
        }
    ];
    
    let html = '';
    
    sections.forEach(section => {
        html += `
            <div class="compare__section">
                <h3 class="compare__section-title">${section.title}</h3>
                ${section.features.map(feature => `
                    <div class="compare__row">
                        <div class="compare__cell compare__cell--feature">${feature.key}</div>
                        ${products.map(product => `
                            <div class="compare__cell">${product[feature.field] || 'N/A'}</div>
                        `).join('')}
                    </div>
                `).join('')}
            </div>
        `;
    });
    
    return html;
}

// Hàm xóa sản phẩm khỏi danh sách so sánh
function removeFromCompare(productId) {
    let compareList = JSON.parse(localStorage.getItem('compareList')) || [];
    compareList = compareList.filter(id => id !== productId);
    localStorage.setItem('compareList', JSON.stringify(compareList));
    
    // Reload trang để cập nhật giao diện
    window.location.reload();
}

// Hàm format giá tiền
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
} 