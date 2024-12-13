<?php include("background/header.php"); ?>
<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "db_langque");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID sản phẩm từ URL
$productID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$maDanhMuc = isset($_GET['MaDanhMuc']) ? (int)$_GET['MaDanhMuc'] : 0;

// Truy vấn chi tiết sản phẩm
$sql = "SELECT sanpham.*, chatlieu.TenChatLieu, danhmucsanpham.TenDanhMuc
        FROM sanpham
        LEFT JOIN chatlieu ON sanpham.MaChatLieu = chatlieu.MaChatLieu
        LEFT JOIN danhmucsanpham ON sanpham.MaDanhMuc = danhmucsanpham.MaDanhMuc
        WHERE sanpham.MaSanPham = $productID";
        
// Truy vấn lấy danh sách danh mục
$sqlDanhMuc = "SELECT MaDanhMuc, TenDanhMuc FROM DanhMucSanPham";
$resultDanhMuc = $conn->query($sqlDanhMuc);
$result = $conn->query($sql);

// Kiểm tra nếu sản phẩm không tồn tại
if ($result->num_rows == 0) {
    echo "<p>Sản phẩm không tồn tại.</p>";
    exit;
}

// Lấy dữ liệu sản phẩm
$product = $result->fetch_assoc();
?>
<style>
/* Container chính */
.product-detail-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    display: flex;
    gap: 30px;
    background-color: #fff;
    border-radius: 10px;
}

/* Hình ảnh sản phẩm */
.product-image img {
    max-width: 450px;
    width: 100%;
    height: auto;
    border-radius: 10px;
    border: 1px solid #eee;
}

/* Thông tin sản phẩm */
.product-info {
    flex: 1;
}

.product-info h2 {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.product-info .price {
    font-size: 20px;
    color: #333;
    font-weight: 200;
    margin-bottom: 20px;
}

.product-meta p {
    font-size: 16px;
    margin: 5px 0;
}

.description {
    font-size: 16px;
    line-height: 1.6;
    margin-top: 15px;
}

/* Nút bấm hành động */
.action-buttons {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.action-buttons button {
    flex: 1;
    padding: 15px 20px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}





.btn-container {
    display: flex;
    justify-content: flex-end;
    /* Canh phải nút */
}

.btn-back {
    padding: 10px 20px;
    background-color: #fff;
    color: #000;
    border-radius: 15px;
    text-decoration: none;
    font-size: 17px;
    transition: background-color 0.3s ease;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid #000;
}

.btn-back:hover {
    background-color: #fda481;

}
</style>

<main>
    <div class="product-detail-container">
        <!-- Cột Hình Ảnh -->
        <div class="product-image">
            <img src="../admin/<?php echo htmlspecialchars($product['HinhAnh']); ?>"
                alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>">
        </div>
        <!-- Cột Thông Tin -->
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['TenSanPham']); ?></h2>
            <p class="price"><?php echo number_format($product['GiaBan'], 0, ',', '.'); ?> VND</p>
            <p class="description"><strong>Mô tả:</strong> <?php echo nl2br(htmlspecialchars($product['MoTa'])); ?></p>
            <p><strong>Chất liệu:</strong> <?php echo htmlspecialchars($product['TenChatLieu']); ?></p>
            <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product['TenDanhMuc']); ?></p>
            <p><strong>Ngày cập nhật:</strong> <?php echo htmlspecialchars($product['NgayCapNhat']); ?></p>

            <div class="btn-container">
                <a href="danhmuc.php?MaDanhMuc=<?php echo $maDanhMuc; ?>" class="btn-back">
                    Quay lại
                </a>
            </div>

        </div>

    </div>
</main>



<?php
// Đóng kết nối
$conn->close();
?>
<?php include("background/footer.php"); ?>