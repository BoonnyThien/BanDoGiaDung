<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <link rel="stylesheet" href="../admin/css/lienhe.css">
</head>
<body>
    <div class="body">
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <iframe id="iframe-trogiup" src="../admin/php/themtrogiup.php" frameborder="0"></iframe>
    </div>
    <div class="form-container sign-in-container">
        <iframe id="iframe-baocao" src="../admin/php/thembaocao.php" frameborder="0"></iframe>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
            <h1>Chào mừng bạn quay trở lại!</h1> <p>Chúng tôi luôn sẵn sàng lắng nghe và khắc phục sự cố của bạn ngay lập tức. Mô tả chi tiết lỗi bạn gặp phải để đội ngũ hỗ trợ của chúng tôi xử lý nhanh chóng</p>
                <button class="ghost" id="signIn">Phản Ánh ngay</button>
            </div>
            <div class="overlay-panel overlay-right">
            <h1>Đăng nhập để trải nghiệm tốt hơn!</h1>
            <p>Chúng tôi rất mong muốn nhận được phản hồi của bạn. Hãy đăng nhập để gửi thông tin chi tiết về yêu cầu của bạn và giúp chúng tôi phục vụ bạn tốt hơn.</p>
                <button class="ghost" id="signUp">Liên Hệ ngay</button>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');
    signUpButton.addEventListener('click', () => {
        container.classList.add('right-panel-active');
    });
    signInButton.addEventListener('click', () => {
        container.classList.remove('right-panel-active');
    });
</script>
</body>
</html>
