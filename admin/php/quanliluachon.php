<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/luachon.css">
</head>
<body>
    <div class="top">
        <button onclick="showOverlay('quanlidanhmuc.php', 'iframe-detail1')">Danh mục</button>
        <button onclick="showOverlay('quanlithuonghieu.php', 'iframe-detail2')">Thương hiệu</button>
        <button onclick="showOverlay('quanlivande.php', 'iframe-detail2')">Vấn đề</button>
    </div>
    <div class="main">
        <iframe id="iframe-detail1" src="" frameborder="0"></iframe>
        <div class="tach"></div>
        <iframe id="iframe-detail2" src="" frameborder="0"></iframe>
    </div>
</body>
<script>
function showOverlay(file, iframeId) {
    var iframe = document.getElementById(iframeId);
    iframe.src = file;
    iframe.onload = function() {
        iframe.contentWindow.scrollTo(0, 0);
    };
}

document.addEventListener('DOMContentLoaded', function() {
    showOverlay('quanlidanhmuc.php', 'iframe-detail1'); 
    showOverlay('quanlithuonghieu.php', 'iframe-detail2'); 
});
</script>
</html>
