<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "db_langque");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn lấy danh sách danh mục
$sqlDanhMuc = "SELECT MaDanhMuc, TenDanhMuc FROM DanhMucSanPham";
$resultDanhMuc = $conn->query($sqlDanhMuc);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="src\styles.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">



</head>

<body>
    <header>
        <div class="logo">
            <img src="src\image\logo.png">
        </div>

        <div class="menu">
            <li><a href="#"><strong>DANH MỤC</strong></a>
                <ul class="sup-menu">
                    <?php if ($resultDanhMuc && $resultDanhMuc->num_rows > 0): ?>
                    <?php while ($danhMuc = $resultDanhMuc->fetch_assoc()): ?>
                    <li>
                        <a href="danhmuc.php?MaDanhMuc=<?php echo $danhMuc['MaDanhMuc']; ?>">
                            <?php echo htmlspecialchars($danhMuc['TenDanhMuc']); ?>
                        </a>
                    </li>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <li><a href="#">Không có danh mục</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <li><a href="index.php"><strong>TRANG CHỦ</strong></a></li>
            <li><a href="gioithieu.php"><strong>GIỚI THIỆU</strong></a></li>
            <li><a href="lienhe.php"><strong>LIÊN HỆ</strong></a></li>
        </div>

        <div class="tool">
            <li class="search-container">
                <form action="index.php" method="GET">
                    <input type="text" name="search" placeholder="Tìm kiếm sản phẩm" class="search-input">
                    <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
                </form>
            </li>
        </div>
    </header>