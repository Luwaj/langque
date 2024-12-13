<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "db_langque");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy mã danh mục từ URL
$maDanhMuc = isset($_GET['MaDanhMuc']) ? (int)$_GET['MaDanhMuc'] : 0;

// Truy vấn sản phẩm theo mã danh mục
$sqlSanPham = "SELECT sanpham.*, danhmucsanpham.TenDanhMuc 
               FROM sanpham
               LEFT JOIN danhmucsanpham ON sanpham.MaDanhMuc = danhmucsanpham.MaDanhMuc
               WHERE sanpham.MaDanhMuc = $maDanhMuc
               ORDER BY sanpham.NgayCapNhat DESC";

$resultSanPham = $conn->query($sqlSanPham);
?>
<style>
/* Container chính */
.card-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    
}

/* Tiêu đề danh mục */
h2 {
    font-size: 30px;
    font-weight: 500;
    color: #000;
    margin-bottom: 20px;
    text-align: center;
}

/* Sản phẩm */
.card {
    background: #fff;
    border: 1px solid #ddd;
    width: 260px;
    height: 355px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.card-img-top {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body {
    padding: 15px;
    text-align: center;
}

.card-title {
    font-size: 25px;
    font-weight: 500px;
    margin-bottom: 10px;
    color: #000;
}

.card-text {
    margin: 15px 0;
    font-size: 17px;
    color: #333;
}
.btn-container{
    display: flex;
    margin-bottom: 10px;
    justify-content: center;
}
/* Nút chi tiết */
.btn-primary {
    padding: 10px 10px;
    background-color: #fff;
    color: #000;
    border-radius: 10px;
    text-decoration: none;
    font-size: 15px;
    transition: background-color 0.3s ease;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    border: 1px solid #000;
}

.btn-primary:hover {
    background-color: #fda481;
    border: 1px solid #000;
}

</style>

<div class="card-container">
    <h2 class="mb-4">
        <?php
            // Hiển thị tên danh mục
            $sqlTenDanhMuc = "SELECT TenDanhMuc FROM DanhMucSanPham WHERE MaDanhMuc = $maDanhMuc";
            $resultTenDanhMuc = $conn->query($sqlTenDanhMuc);
            echo ($resultTenDanhMuc && $resultTenDanhMuc->num_rows > 0) 
                ? $resultTenDanhMuc->fetch_assoc()['TenDanhMuc']
                : "Danh mục không tồn tại";
            ?>
    </h2>
    <div class="row">
        <?php if ($resultSanPham && $resultSanPham->num_rows > 0): ?>
        <?php while ($product = $resultSanPham->fetch_assoc()): ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="../admin/<?php echo htmlspecialchars($product['HinhAnh']); ?>" class="card-img-top"
                    alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product['TenSanPham']); ?></h5>
                    <p class="card-text"><?php echo number_format($product['GiaBan'], 0, ',', '.'); ?> VND</p>
                    <div class="btn-container">
                        <a href="chitietsanphamdm.php?id=<?php echo $product['MaSanPham']; ?>&MaDanhMuc=<?php echo $maDanhMuc; ?>"
                        class="btn btn-primary"> Xem chi tiết</a>
                    </div>
                    

                </div>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <p>Không có sản phẩm trong danh mục này.</p>
        <?php endif; ?>
    </div>
</div>