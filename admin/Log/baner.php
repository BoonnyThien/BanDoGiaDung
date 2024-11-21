<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner</title>
    <style>
        .slideshow-container {
            width: 100%;
            height: 70px;
            position: relative;
            overflow: hidden;
            margin: 0;
            border: 1px solid lightsalmon;
        }

        .slide {
            width: 100%;
            height: 100%; /* Chiều cao của slide phải khớp với cha */
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
            display: flex; /* Đảm bảo các ảnh chia đều nhau */
            transition: opacity 0.5s;
        }

        .slide.active {
            opacity: 1;
        }

        .slide img {
            width: 20%; /* Chia đều các ảnh trong slide */
            height: 100%; /* Chiều cao hình ảnh phải khớp với slide */
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="slideshow-container">
        <div class="slide active">
            <img src="../assets/img/slider_img/slide1.png" alt="Image 1">
            <img src="../assets/img/slider_img/slide2.png" alt="Image 2">
            <img src="../assets/img/slider_img/slide3.png" alt="Image 3">
            <img src="../assets/img/slider_img/slide4.png" alt="Image 4">
            <img src="../assets/img/slider_img/slide5.png" alt="Image 5">
        </div>
        <div class="slide">
            <img src="../assets/img/slider_img/slide2.png" alt="Image 2">
            <img src="../assets/img/slider_img/slide3.png" alt="Image 3">
            <img src="../assets/img/slider_img/slide4.png" alt="Image 4">
            <img src="../assets/img/slider_img/slide5.png" alt="Image 5">
            <img src="../assets/img/slider_img/slide1.png" alt="Image 1">
        </div>
        <div class="slide">
            <img src="../assets/img/slider_img/slide3.png" alt="Image 3">
            <img src="../assets/img/slider_img/slide4.png" alt="Image 4">
            <img src="../assets/img/slider_img/slide5.png" alt="Image 5">
            <img src="../assets/img/slider_img/slide1.png" alt="Image 1">
            <img src="../assets/img/slider_img/slide2.png" alt="Image 2">
        </div>
        <div class="slide">
            <img src="../assets/img/slider_img/slide4.png" alt="Image 4">
            <img src="../assets/img/slider_img/slide5.png" alt="Image 5">
            <img src="../assets/img/slider_img/slide1.png" alt="Image 1">
            <img src="../assets/img/slider_img/slide2.png" alt="Image 2">
            <img src="../assets/img/slider_img/slide3.png" alt="Image 3">
        </div>
        <div class="slide">
            <img src="../assets/img/slider_img/slide5.png" alt="Image 5">
            <img src="../assets/img/slider_img/slide1.png" alt="Image 1">
            <img src="../assets/img/slider_img/slide2.png" alt="Image 2">
            <img src="../assets/img/slider_img/slide3.png" alt="Image 3">
            <img src="../assets/img/slider_img/slide4.png" alt="Image 4">
        </div>
    </div>
    <script>
        let count = 0;
        const slide = document.querySelectorAll(".slide");
        function showSlide(direction = 1) {
            slide[count].classList.remove("active");
            count = (count + direction + slide.length) % slide.length;
            slide[count].classList.add("active");
        }
        setInterval(() => showSlide(1), 3000);
    </script>
</body>
</html>
