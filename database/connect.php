<?php
$con = mysqli_connect("localhost","root","","web_giadung");

if (mysqli_connect_errno()) {
  echo "Kết nối thất bại: " . mysqli_connect_error();
  exit();
}
mysqli_set_charset($con,"utf8");
?>