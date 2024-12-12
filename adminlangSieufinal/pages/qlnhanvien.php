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

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/qlnhanvien.css">
    <title>Quản Lý Nhân Viên</title>
</head>

<body>
    <div id="topbar">
    </div>
    <div id="sidebar">
    </div>

    <div class="admin-content">
        <div class="admin-leaner">
            <h1 class="leaner-title">Quản Lý Nhân Viên</h1>
            <div class="search-user">
                <form method="GET" action="">
                    <input type="text" name="search" class="search-input" placeholder="Tìm kiếm nhân viên..."
                        value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                </form>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Mã Nhân Viên</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số Điện Thoại</th>
                        <th>Địa Chỉ</th>
                        <th>Ngày Sinh</th>
                        <th>Giới Tính</th>
                        <th>Số Căn Cước Công Dân</th>
                        <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    $sql = "SELECT * FROM nhanvien WHERE HoTen LIKE '%$search%'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['MaNhanVien']}</td>
                                // <td><img src='/uploads/{$row['avatar']}' alt='Avatar' class='table-image'></td>
                                <td>{$row['HoTen']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['SoDienThoai']}</td>
                                <td>{$row['DiaChi']}</td>
                                <td>{$row['NgaySinh']}</td>
                                <td>{$row['GioiTinh']}</td>
                                <td>{$row['CCCD']}</td>
                                <td>{$row['TrangThai']}</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>Không tìm thấy người dùng</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function loadHTML(url, elementId) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById(elementId).innerHTML = data;
                })
                .catch(error => console.error('Error loading HTML:', error));
        }



        function showAddForm() {
            $('#product-form-container').modal('show');
            document.getElementById('form-title').innerText = 'Thêm sản phẩm';
            document.getElementById('add-button').classList.remove('d-none');
            document.getElementById('update-button').classList.add('d-none');
            document.getElementById('product-form').reset();
        }

        function editProduct(product) {
            $('#product-form-container').modal('show');
            document.getElementById('product-id').value = product.MaSanPham;
            document.getElementById('TenSanPham').value = product.TenSanPham;
            document.getElementById('MoTa').value = product.MoTa;
            document.getElementById('GiaBan').value = product.GiaBan;
            document.getElementById('existing-image').value = product.HinhAnh;

            document.getElementById('form-title').innerText = 'Cập nhật sản phẩm';
            document.getElementById('add-button').classList.add('d-none');
            document.getElementById('update-button').classList.remove('d-none');
        }
        // Hàm xác nhận xóa sản phẩm
        function confirmDelete() {
            return confirm("Bạn có chắc chắn muốn xóa sản phẩm này?");
        }

        loadHTML('app/topbar.php', 'topbar');
        loadHTML('app/sidebar.php', 'sidebar');
    </script>
</body>

</html>