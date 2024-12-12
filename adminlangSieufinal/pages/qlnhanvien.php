<?php
// db.php - Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_langque";  // Thay thế "db_langque" bằng tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý thêm nhân viên
if (isset($_POST['add_employee'])) {
    $hoTen = $_POST['HoTen'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];
    $ngaySinh = $_POST['NgaySinh'];
    $gioiTinh = $_POST['GioiTinh'];
    $cccd = $_POST['CCCD'];
    $trangThai = $_POST['TrangThai'];

    // Thực hiện câu lệnh SQL để thêm nhân viên
    $sql = "INSERT INTO nhanvien (HoTen, Email, SoDienThoai, DiaChi, NgaySinh, GioiTinh, CCCD, TrangThai) 
            VALUES ('$hoTen', '$email', '$soDienThoai', '$diaChi', '$ngaySinh', '$gioiTinh', '$cccd', '$trangThai')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm nhân viên thành công!');</script>";
    } else {
        echo "<script>alert('Lỗi: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}

// Xử lý cập nhật nhân viên
if (isset($_POST['update_employee'])) {
    $maNhanVien = $_POST['MaNhanVien'];
    $hoTen = $_POST['HoTen'];
    $email = $_POST['Email'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];
    $ngaySinh = $_POST['NgaySinh'];
    $gioiTinh = $_POST['GioiTinh'];
    $cccd = $_POST['CCCD'];
    $trangThai = $_POST['TrangThai'];

    // Thực hiện câu lệnh SQL để cập nhật nhân viên
    $sql = "UPDATE nhanvien 
            SET HoTen = '$hoTen', Email = '$email', SoDienThoai = '$soDienThoai', DiaChi = '$diaChi', NgaySinh = '$ngaySinh', 
                GioiTinh = '$gioiTinh', CCCD = '$cccd', TrangThai = '$trangThai' 
            WHERE MaNhanVien = '$maNhanVien'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật nhân viên thành công!'); window.location.reload();</script>";
    } else {
        echo "<script>alert('Lỗi: " . $sql . "<br>" . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/qlnhanvien.css">
    <title>Quản Lý Nhân Viên</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div id="topbar"></div>
    <div id="sidebar"></div>

    <div class="admin-content" style=" margin-left: 250px">
        <div class="admin-leaner" style="width:1250px; ">
            <h1 class="leaner-title">Quản Lý Nhân Viên</h1>

            <div class="search-user">
                <form method="GET" class="form-inline mb-4">
                    <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm nhân viên" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>

            <button class="btn btn-success" style="margin-top:1rem" onclick="showAddForm()">Thêm Nhân Viên</button>

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
                        <th>Thao Tác</th>
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
                                <td>{$row['HoTen']}</td>
                                <td>{$row['Email']}</td>
                                <td>{$row['SoDienThoai']}</td>
                                <td>{$row['DiaChi']}</td>
                                <td>{$row['NgaySinh']}</td>
                                <td>{$row['GioiTinh']}</td>
                                <td>{$row['CCCD']}</td>
                                <td>{$row['TrangThai']}</td>
                                <td><button class='btn btn-warning' onclick='editEmployee({$row['MaNhanVien']}, \"{$row['HoTen']}\", \"{$row['Email']}\", \"{$row['SoDienThoai']}\", \"{$row['DiaChi']}\", \"{$row['NgaySinh']}\", \"{$row['GioiTinh']}\", \"{$row['CCCD']}\", \"{$row['TrangThai']}\")'>Sửa</button></td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10'>Không tìm thấy nhân viên</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Thêm Nhân Viên -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Thêm Nhân Viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="HoTen">Họ và tên:</label>
                            <input type="text" name="HoTen" id="HoTen" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Email">Email:</label>
                            <input type="email" name="Email" id="Email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="SoDienThoai">Số điện thoại:</label>
                            <input type="text" name="SoDienThoai" id="SoDienThoai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="DiaChi">Địa chỉ:</label>
                            <input type="text" name="DiaChi" id="DiaChi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="NgaySinh">Ngày sinh:</label>
                            <input type="date" name="NgaySinh" id="NgaySinh" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="GioiTinh">Giới tính:</label>
                            <select name="GioiTinh" id="GioiTinh" class="form-control" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="CCCD">Số Căn Cước Công Dân:</label>
                            <input type="text" name="CCCD" id="CCCD" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="TrangThai">Trạng thái:</label>
                            <select name="TrangThai" id="TrangThai" class="form-control" required>
                                <option value="Đang làm">Đang làm</option>
                                <option value="Nghỉ việc">Nghỉ việc</option>
                            </select>
                        </div>
                        <button type="submit" name="add_employee" class="btn btn-success">Thêm Nhân Viên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Sửa Nhân Viên -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Sửa Nhân Viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <input type="hidden" name="MaNhanVien" id="MaNhanVien">
                        <div class="form-group">
                            <label for="HoTen">Họ và tên:</label>
                            <input type="text" name="HoTen" id="HoTen" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="Email">Email:</label>
                            <input type="email" name="Email" id="Email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="SoDienThoai">Số điện thoại:</label>
                            <input type="text" name="SoDienThoai" id="SoDienThoai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="DiaChi">Địa chỉ:</label>
                            <input type="text" name="DiaChi" id="DiaChi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="NgaySinh">Ngày sinh:</label>
                            <input type="date" name="NgaySinh" id="NgaySinh" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="GioiTinh">Giới tính:</label>
                            <select name="GioiTinh" id="GioiTinh" class="form-control" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="CCCD">Số Căn Cước Công Dân:</label>
                            <input type="text" name="CCCD" id="CCCD" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="TrangThai">Trạng thái:</label>
                            <select name="TrangThai" id="TrangThai" class="form-control" required>
                                <option value="Đang làm">Đang làm</option>
                                <option value="Nghỉ việc">Nghỉ việc</option>
                            </select>
                        </div>
                        <button type="submit" name="update_employee" class="btn btn-warning">Cập Nhật Nhân Viên</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
            $('#addEmployeeModal').modal('show');
        }

        function editEmployee(MaNhanVien, HoTen, Email, SoDienThoai, DiaChi, NgaySinh, GioiTinh, CCCD, TrangThai) {
            // Điền thông tin vào các trường trong modal
            document.getElementById('MaNhanVien').value = MaNhanVien;
            document.getElementById('HoTen').value = HoTen;
            document.getElementById('Email').value = Email;
            document.getElementById('SoDienThoai').value = SoDienThoai;
            document.getElementById('DiaChi').value = DiaChi;
            document.getElementById('NgaySinh').value = NgaySinh;
            document.getElementById('GioiTinh').value = GioiTinh;
            document.getElementById('CCCD').value = CCCD;
            document.getElementById('TrangThai').value = TrangThai;

            // Mở modal
            $('#editEmployeeModal').modal('show');
        }
        
    loadHTML('app/topbar.php', 'topbar');
    loadHTML('app/sidebar.php', 'sidebar');
    </script>
</body>

</html>
