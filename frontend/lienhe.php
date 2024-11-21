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
    .lienhe{
        margin: 130px 0 20px;
        font-size: 15px;
    }

</style>
<body>
<?php
    // include_once __DIR__.'/component/header.php';
    include("components/header.php");
?>
    <div class="lienhe">
    <?php include_once "../admin/php/lienhe.php"; ?>
    </div>
    <?php
    // include_once __DIR__.'/component/footer.php';
    include("components/footer.php");
    ?>
</body>