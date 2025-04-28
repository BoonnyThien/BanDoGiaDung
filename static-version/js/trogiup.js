// static-version/js/trogiup.js
function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Hàm hiển thị form hỗ trợ (dùng trong taikhoan.js)
function renderSupportForm(container) {
    return new Promise((resolve, reject) => {
        // Tải dữ liệu JSON
        fetch('../web_giadung.json')
            .then(response => response.json())
            .then(jsonData => {
                // Lấy dữ liệu từ JSON
                const problems = jsonData.tables.find(table => table.name === "tbl_problem").data;

                // Kiểm tra trạng thái đăng nhập (dùng localStorage)
                const loggedInUser = JSON.parse(localStorage.getItem('loggedInUser')) || null;
                const userId = loggedInUser ? loggedInUser.user_id : "0";

                // Tạo form hỗ trợ
                if (loggedInUser) {
                    // Form "Gửi báo cáo" (đã đăng nhập)
                    container.innerHTML = `
                        <h2>Gửi báo cáo</h2>
                        <form id="report-form">
                            <label for="problem_id">Vấn đề:
                                <select id="problem_id" name="problem_id" required>
                                    ${problems.map(problem => `<option value="${problem.problem_id}">${problem.problem_name}</option>`).join('')}
                                </select>
                            </label><br>
                            <label for="report_content">Nội dung:
                                <textarea id="report_content" name="report_content" required></textarea>
                            </label><br>
                            <label class="bt">
                                <input type="submit" id="tick" name="add" value="Gửi">
                            </label>
                        </form>
                    `;
                } else {
                    // Form "Gửi trợ giúp" (chưa đăng nhập)
                    container.innerHTML = `
                        <h2>Gửi trợ giúp</h2>
                        <form id="help-form">
                            <label for="problem_id">Vấn đề:
                                <select id="problem_id" name="problem_id" required>
                                    ${problems.map(problem => `<option value="${problem.problem_id}">${problem.problem_name}</option>`).join('')}
                                </select>
                            </label><br>
                            <label for="name">Họ và tên:
                                <input type="text" id="name" name="name" required>
                            </label><br>
                            <label for="phone">Số điện thoại:
                                <input type="tel" id="phone" name="phone" required pattern="[0-9]{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
                            </label><br>
                            <label for="email">Email:
                                <input type="email" id="email" name="email" required>
                            </label><br>
                            <label for="report_content">Nội dung:
                                <textarea id="report_content" name="report_content" required></textarea>
                            </label><br>
                            <label class="bt">
                                <input type="submit" id="tick" name="add" value="Gửi">
                            </label>
                        </form>
                    `;
                }

                // Xử lý form "Gửi báo cáo" (đã đăng nhập)
                const reportForm = $('#report-form');
                if (reportForm) {
                    reportForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        const problemId = reportForm.querySelector('#problem_id').value;
                        const reportContent = reportForm.querySelector('#report_content').value.trim();

                        // Validate dữ liệu
                        if (!reportContent) {
                            showError('Vui lòng điền đầy đủ thông tin!');
                            return;
                        }
                        if (reportContent.length < 10) {
                            showError('Nội dung báo cáo phải dài hơn 10 ký tự!');
                            return;
                        }

                        // Lưu báo cáo vào localStorage
                        const reports = JSON.parse(localStorage.getItem('reports')) || [];
                        const newReport = {
                            user_id: userId,
                            problem_id: problemId,
                            report_content: reportContent,
                            report_date: new Date().toISOString(),
                            report_status: 'pending'
                        };
                        reports.push(newReport);
                        localStorage.setItem('reports', JSON.stringify(reports));

                        showsuccess('Gửi báo cáo thành công!');
                        setTimeout(() => {
                            reportForm.reset();
                        }, 2000);
                    });
                }

                // Xử lý form "Gửi trợ giúp" (chưa đăng nhập)
                const helpForm = $('#help-form');
                if (helpForm) {
                    helpForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        const problemId = helpForm.querySelector('#problem_id').value;
                        const name = helpForm.querySelector('#name').value.trim();
                        const phone = helpForm.querySelector('#phone').value.trim();
                        const email = helpForm.querySelector('#email').value.trim();
                        const reportContent = helpForm.querySelector('#report_content').value.trim();

                        // Validate dữ liệu
                        if (!name || !phone || !email || !reportContent) {
                            showError('Vui lòng điền đầy đủ thông tin!');
                            return;
                        }
                        if (name.length < 3) {
                            showError('Tên phải có ít nhất 3 ký tự!');
                            return;
                        }
                        if (!/^[0-9]{10}$/.test(phone)) {
                            showError('Số điện thoại không hợp lệ!');
                            return;
                        }
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                            showError('Email không hợp lệ!');
                            return;
                        }
                        if (reportContent.length < 10) {
                            showError('Nội dung báo cáo phải dài hơn 10 ký tự!');
                            return;
                        }

                        // Tạo nội dung báo cáo
                        const fullContent = `
                            <body>
                                <p><strong>Người gửi:</strong> ${name}</p>
                                <p><strong>Số điện thoại:</strong> ${phone}</p>
                                <p><strong>Email:</strong> ${email}</p>
                                <p><strong>Nội dung:</strong> ${reportContent}</p>
                            </body>
                        `;

                        // Lưu báo cáo vào localStorage
                        const reports = JSON.parse(localStorage.getItem('reports')) || [];
                        const newReport = {
                            user_id: "0", // Người dùng chưa đăng nhập
                            problem_id: problemId,
                            report_content: fullContent,
                            report_date: new Date().toISOString(),
                            report_status: 'pending'
                        };
                        reports.push(newReport);
                        localStorage.setItem('reports', JSON.stringify(reports));

                        showsuccess('Gửi trợ giúp thành công!');
                        setTimeout(() => {
                            helpForm.reset();
                        }, 2000);
                    });
                }

                resolve();
            })
            .catch(error => {
                console.error('Error loading data:', error);
                reject(error);
            });
    });
}

// Hàm hiển thị thông báo thành công (từ message.js)
function showsuccess(message) {
    const toast = document.createElement('div');
    toast.className = 'toast toast--success';
    toast.innerHTML = `
        <div class="toast__icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="toast__body">
            <h3 class="toast__title">Thành công</h3>
            <p class="toast__msg">${message}</p>
        </div>
        <div class="toast__close">
            <i class="fas fa-times"></i>
        </div>
    `;
    const toastContainer = $('#toast');
    if (toastContainer) {
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
}

// Hàm hiển thị thông báo lỗi
function showError(message) {
    const toast = document.createElement('div');
    toast.className = 'toast toast--error';
    toast.innerHTML = `
        <div class="toast__icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="toast__body">
            <h3 class="toast__title">Lỗi</h3>
            <p class="toast__msg">${message}</p>
        </div>
        <div class="toast__close">
            <i class="fas fa-times"></i>
        </div>
    `;
    const toastContainer = $('#toast');
    if (toastContainer) {
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
}