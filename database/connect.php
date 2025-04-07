<?php
$url = parse_url(getenv("JAWSDB_URL"));

$host = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);

$con = mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno()) {
  echo "Kết nối thất bại: " . mysqli_connect_error();
  exit();
}
mysqli_set_charset($con,"utf8");
?>