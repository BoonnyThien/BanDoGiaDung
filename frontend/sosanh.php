<?php
include_once('../database/connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop đồ gia dụng</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"> -->
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/message.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>
<style>
    .sosanh_body {
        margin: 130px 0 0;
        padding: 0 200px;
        font-size: 11px;
        overflow: hidden;
    }

    iframe {
        width: 100%;
        border: none;
        overflow: hidden;
    }

    .header__search-btn-icon {
        display: none;
    }

    .header__search-btn::before {
        content: "✨";
        width: 100px;
        color: white;
        font-size: 18px;
        background-color: white;
        border-radius: 5px;
        padding: 0 19px;
    }
</style>

<body>
    <?php include("components/header.php"); ?>
    <div class="sosanh_body">
        <div class="ss_body">
            <?php include_once "../admin/Log/baner.php"; ?>
        </div>
        <iframe id="sosanhIframe" src="../admin/php/sosanh.php" frameborder="0"></iframe>
    </div>
    <?php include("components/footer.php");
    ?>
    <script>
        function resizeIframe(iframe) {
            // iframe.style.height = 'auto';
            // iframe.offsetHeight;
            iframe.style.height = iframe.contentWindow.document.documentElement.scrollHeight + 'px';

        }
        document.addEventListener("DOMContentLoaded", function() {
            var iframe = document.getElementById('sosanhIframe');
            iframe.onload = function() {
                resizeIframe(iframe);
            }
            // Nếu cần cập nhật kích thước theo các sự kiện khác
            iframe.contentWindow.addEventListener('scroll', function() {
                resizeIframe(iframe);
            });
        });
    </script>
</body>

</html>