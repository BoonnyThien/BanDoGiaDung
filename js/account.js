// Chuyển đổi giữa form đăng nhập và đăng ký
function toggleForms() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const accountInfo = document.getElementById('accountInfo');
    
    loginForm.classList.toggle('hidden');
    registerForm.classList.toggle('hidden');
    accountInfo.classList.add('hidden');
}

// Xử lý đăng nhập
function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('loginUsername').value;
    const password = document.getElementById('loginPassword').value;
    
    if (loginUser(username, password)) {
        showAccountInfo();
    }
    
    return false;
}

// Xử lý đăng ký
function handleRegister(event) {
    event.preventDefault();
    
    const username = document.getElementById('registerUsername').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password !== confirmPassword) {
        showMessage('Mật khẩu xác nhận không khớp');
        return false;
    }
    
    if (registerUser(username, password, email)) {
        toggleForms(); // Chuyển về form đăng nhập
    }
    
    return false;
}

// Xử lý đăng xuất
function handleLogout() {
    logoutUser();
    showLoginForm();
}

// Hiển thị form đăng nhập
function showLoginForm() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const accountInfo = document.getElementById('accountInfo');
    
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    accountInfo.classList.add('hidden');
}

// Hiển thị thông tin tài khoản
function showAccountInfo() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const accountInfo = document.getElementById('accountInfo');
    
    loginForm.classList.add('hidden');
    registerForm.classList.add('hidden');
    accountInfo.classList.remove('hidden');
    
    const user = getCurrentUser();
    document.getElementById('displayUsername').textContent = user.username;
    document.getElementById('displayEmail').textContent = user.email;
    document.getElementById('displayCreatedAt').textContent = new Date(user.created_at).toLocaleDateString('vi-VN');
}

// Kiểm tra trạng thái đăng nhập khi tải trang
document.addEventListener('DOMContentLoaded', () => {
    if (isLoggedIn()) {
        showAccountInfo();
    } else {
        showLoginForm();
    }
}); 