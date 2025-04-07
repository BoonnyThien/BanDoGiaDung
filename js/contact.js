document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Lấy dữ liệu từ form
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            subject: document.getElementById('subject').value,
            message: document.getElementById('message').value
        };
        
        // Validate dữ liệu
        if (!validateForm(formData)) {
            return;
        }
        
        // Hiển thị loading
        const submitButton = contactForm.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Đang gửi...';
        
        // Giả lập gửi form (trong thực tế sẽ gửi đến server)
        setTimeout(() => {
            // Lưu thông tin liên hệ vào localStorage
            saveContactMessage(formData);
            
            // Hiển thị thông báo thành công
            showNotification('Gửi tin nhắn thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.', 'success');
            
            // Reset form
            contactForm.reset();
            
            // Khôi phục nút submit
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }, 1500);
    });
});

// Hàm validate form
function validateForm(data) {
    // Validate tên
    if (data.name.trim().length < 2) {
        showNotification('Vui lòng nhập họ tên hợp lệ', 'error');
        return false;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(data.email)) {
        showNotification('Vui lòng nhập email hợp lệ', 'error');
        return false;
    }
    
    // Validate số điện thoại
    const phoneRegex = /^[0-9]{10,11}$/;
    if (!phoneRegex.test(data.phone.replace(/\s/g, ''))) {
        showNotification('Vui lòng nhập số điện thoại hợp lệ', 'error');
        return false;
    }
    
    // Validate tiêu đề
    if (data.subject.trim().length < 5) {
        showNotification('Vui lòng nhập tiêu đề hợp lệ', 'error');
        return false;
    }
    
    // Validate nội dung
    if (data.message.trim().length < 10) {
        showNotification('Vui lòng nhập nội dung tin nhắn hợp lệ', 'error');
        return false;
    }
    
    return true;
}

// Hàm lưu tin nhắn vào localStorage
function saveContactMessage(data) {
    // Lấy danh sách tin nhắn cũ
    let messages = JSON.parse(localStorage.getItem('contactMessages')) || [];
    
    // Thêm tin nhắn mới
    messages.push({
        ...data,
        date: new Date().toISOString()
    });
    
    // Lưu lại vào localStorage
    localStorage.setItem('contactMessages', JSON.stringify(messages));
}

// Hàm hiển thị thông báo
function showNotification(message, type = 'success') {
    // Tạo element thông báo
    const notification = document.createElement('div');
    notification.className = `notification notification--${type}`;
    notification.textContent = message;
    
    // Thêm vào body
    document.body.appendChild(notification);
    
    // Hiển thị animation
    setTimeout(() => {
        notification.classList.add('notification--show');
    }, 100);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        notification.classList.remove('notification--show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Thêm CSS cho notification
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        border-radius: 5px;
        color: white;
        font-size: 1.4rem;
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
        z-index: 1000;
    }
    
    .notification--show {
        transform: translateX(0);
    }
    
    .notification--success {
        background-color: #4CAF50;
    }
    
    .notification--error {
        background-color: #f44336;
    }
`;
document.head.appendChild(style); 