<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "db_langque");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nhận dữ liệu từ GET
$searchKeyword = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';


// Truy vấn SQL
$sql = "SELECT sanpham.*, chatlieu.TenChatLieu, danhmucsanpham.TenDanhMuc 
        FROM sanpham
        LEFT JOIN chatlieu ON sanpham.MaChatLieu = chatlieu.MaChatLieu
        LEFT JOIN danhmucsanpham ON sanpham.MaDanhMuc = danhmucsanpham.MaDanhMuc
        WHERE 1=1";

if (!empty($searchKeyword)) {
    $sql .= " AND (sanpham.TenSanPham LIKE '%$searchKeyword%' OR danhmucsanpham.TenDanhMuc LIKE '%$searchKeyword%')";
}

$sql .= " ORDER BY sanpham.NgayCapNhat DESC";
$result = $conn->query($sql);
?>
<style>
/* Container card */
.card-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    /* 4 cột */
    gap: 20px;
    /* Khoảng cách giữa các card */
    padding: 20px;
}

/* Card sản phẩm */
.product-card {
    background: #fff;
    border: 1px solid #ddd;
    width: 260px;
    height: 355px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}


/* Hiệu ứng hover */
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

/* Ảnh sản phẩm */
.product-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

/* Thông tin sản phẩm */
.product-info {
    padding: 15px;
}

.product-info h2 {
    font-size: 25px;
    font-weight: 500px;
    margin-bottom: 10px;
    color: #000;
}

.product-info p {
    margin: 5px 0;
    font-size: 17px;
    color: #333;
}
.btn-container{
    display: flex;
    margin-bottom: 10px;
    justify-content: center;
}
/* Nút chi tiết */
.btn-details {
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

.btn-details:hover {
    background-color: #fda481;
}

/* Responsive: 1 cột trên màn hình nhỏ */
@media (max-width: 768px) {
    .card-container {
        grid-template-columns: repeat(1, 1fr);
        /* 1 cột */
    }
}

.product-list {
    margin: 50px 150px;
}
</style>
<section id="slider">
    <div class="slider-container">
        <img src="src\image\bg.png"></img>
    </div>
</section>
<main>
    <div class="product-list">
        <?php if ($result->num_rows > 0): ?>
        <div class="card-container">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <img src="../admin/<?php echo htmlspecialchars($row['HinhAnh']); ?>"
                    alt="<?php echo htmlspecialchars($row['TenSanPham']); ?>" class="product-img">
                <div class="product-info text-center">
                    <h2><?php echo htmlspecialchars($row['TenSanPham']); ?></h2>
                    <p><?php echo number_format($row['GiaBan'], 0, ',', '.'); ?> VND</p>
                </div>
                <div class="btn-container">
                    <a href="product_detail.php?id=<?php echo $row['MaSanPham']; ?>" class="btn-details">Xem chi
                        tiết</a>
                </div>

            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p>Không tìm thấy sản phẩm nào phù hợp.</p>
        <?php endif; ?>
    </div>
</main>