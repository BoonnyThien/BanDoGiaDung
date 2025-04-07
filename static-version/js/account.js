// Kiểm tra trạng thái đăng nhập
function checkLoginStatus() {
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const authSection = document.getElementById('auth-section');
    const accountDashboard = document.getElementById('account-dashboard');
    
    if (isLoggedIn) {
        authSection.style.display = 'none';
        accountDashboard.style.display = 'grid';
        loadUserProfile();
    } else {
        authSection.style.display = 'block';
        accountDashboard.style.display = 'none';
    }
}

// Xử lý chuyển đổi tab đăng nhập/đăng ký
document.querySelectorAll('.auth-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        // Xóa active class từ tất cả các tab
        document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
        
        // Thêm active class cho tab được chọn
        tab.classList.add('active');
        const formId = `${tab.dataset.tab}-form`;
        document.getElementById(formId).classList.add('active');
    });
});

// Xử lý đăng nhập
document.getElementById('login-btn').addEventListener('click', () => {
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;
    
    if (!email || !password) {
        showError('Vui lòng điền đầy đủ thông tin');
        return;
    }
    
    // Trong thực tế, đây sẽ là API call
    // Đối với demo, chúng ta sẽ giả lập đăng nhập thành công
    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('userEmail', email);
    checkLoginStatus();
    showSuccess('Đăng nhập thành công!');
});

// Xử lý đăng ký
document.getElementById('register-btn').addEventListener('click', () => {
    const name = document.getElementById('register-name').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('register-confirm-password').value;
    
    if (!name || !email || !password || !confirmPassword) {
        showError('Vui lòng điền đầy đủ thông tin');
        return;
    }
    
    if (password !== confirmPassword) {
        showError('Mật khẩu xác nhận không khớp');
        return;
    }
    
    // Trong thực tế, đây sẽ là API call
    // Đối với demo, chúng ta sẽ giả lập đăng ký thành công
    localStorage.setItem('isLoggedIn', 'true');
    localStorage.setItem('userEmail', email);
    localStorage.setItem('userName', name);
    checkLoginStatus();
    showSuccess('Đăng ký thành công!');
});

// Xử lý đăng xuất
document.getElementById('logout-btn').addEventListener('click', (e) => {
    e.preventDefault();
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userEmail');
    localStorage.removeItem('userName');
    checkLoginStatus();
    showSuccess('Đăng xuất thành công!');
});

// Xử lý chuyển đổi section trong dashboard
document.querySelectorAll('.account-menu a[data-section]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Xóa active class từ tất cả các menu item
        document.querySelectorAll('.account-menu a').forEach(a => a.classList.remove('active'));
        document.querySelectorAll('.account-section').forEach(section => section.style.display = 'none');
        
        // Thêm active class cho menu item được chọn
        link.classList.add('active');
        const sectionId = `${link.dataset.section}-section`;
        document.getElementById(sectionId).style.display = 'block';
    });
});

// Load thông tin người dùng
function loadUserProfile() {
    const userName = localStorage.getItem('userName');
    const userEmail = localStorage.getItem('userEmail');
    
    if (userName) {
        document.getElementById('profile-name').value = userName;
    }
    if (userEmail) {
        document.getElementById('profile-email').value = userEmail;
    }
}

// Xử lý lưu thông tin cá nhân
document.querySelector('.save-btn').addEventListener('click', () => {
    const name = document.getElementById('profile-name').value;
    const email = document.getElementById('profile-email').value;
    const phone = document.getElementById('profile-phone').value;
    const birthday = document.getElementById('profile-birthday').value;
    
    if (!name || !email) {
        showError('Vui lòng điền đầy đủ thông tin bắt buộc');
        return;
    }
    
    // Trong thực tế, đây sẽ là API call
    localStorage.setItem('userName', name);
    localStorage.setItem('userEmail', email);
    localStorage.setItem('userPhone', phone);
    localStorage.setItem('userBirthday', birthday);
    
    showSuccess('Lưu thông tin thành công!');
});

// Hiển thị thông báo lỗi
function showError(message) {
    // Trong thực tế, bạn có thể sử dụng một thư viện toast hoặc modal
    alert(message);
}

// Hiển thị thông báo thành công
function showSuccess(message) {
    // Trong thực tế, bạn có thể sử dụng một thư viện toast hoặc modal
    alert(message);
}

// Khởi tạo trang
document.addEventListener('DOMContentLoaded', () => {
    checkLoginStatus();
}); 