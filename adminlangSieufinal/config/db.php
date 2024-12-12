<?php
// db.php - Kết nối cơ sở dữ liệu

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_langque";  // Thay thế "ten_database" bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
