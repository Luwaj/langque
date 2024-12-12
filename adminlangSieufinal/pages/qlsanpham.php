<?php
// db.php - Kết nối cơ sở dữ liệu

$servername = 'mysql:host=localhost;dbname=db_langque';
$username = "root";
$password = "";
$dbname = "db_langque";  // Thay thế "db_langque" bằng tên cơ sở dữ liệu của bạn

try {
    $pdo = new PDO($servername, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
// Xử lý tìm kiếm
$search = $_GET['search'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM sanpham WHERE MaSanPham LIKE ? OR TenSanPham LIKE ?");
$stmt->execute(["%$search%", "%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý thêm, sửa, xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thêm sản phẩm
    if (isset($_POST['add_product'])) {
        $TenSanPham = $_POST['TenSanPham'];
        $MoTa = $_POST['MoTa'];
        $GiaBan = $_POST['GiaBan'];

        // Xử lý hình ảnh
        if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES['HinhAnh']['name']);
            move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $targetFile);
        } else {
            $targetFile = null;
        }

        $stmt = $pdo->prepare("INSERT INTO sanpham (TenSanPham, MoTa, GiaBan, HinhAnh, NgayCapNhat) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$TenSanPham, $MoTa, $GiaBan, $targetFile]);
    } // Cập nhật sản phẩm
    elseif (isset($_POST['update_product'])) {
        $MaSanPham = $_POST['MaSanPham'];
        $TenSanPham = $_POST['TenSanPham'];
        $MoTa = $_POST['MoTa'];
        $GiaBan = $_POST['GiaBan'];

        // Xử lý hình ảnh cập nhật
        if (isset($_FILES['HinhAnh']) && $_FILES['HinhAnh']['error'] == 0) {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES['HinhAnh']['name']);
            move_uploaded_file($_FILES['HinhAnh']['tmp_name'], $targetFile);
        } else {
            $targetFile = $_POST['existing_image']; // Giữ hình ảnh cũ nếu không cập nhật mới
        }

        $stmt = $pdo->prepare("UPDATE sanpham SET TenSanPham = ?, MoTa = ?, GiaBan = ?, HinhAnh = ?, NgayCapNhat = NOW() WHERE MaSanPham = ?");
        $stmt->execute([$TenSanPham, $MoTa, $GiaBan, $targetFile, $MaSanPham]);
    } // Xóa sản phẩm
    elseif (isset($_POST['delete_product'])) {
        $MaSanPham = $_POST['MaSanPham'];

        $stmt = $pdo->prepare("DELETE FROM sanpham WHERE MaSanPham = ?");
        $stmt->execute([$MaSanPham]);
    }
}

// Xử lý tìm kiếm
$search = $_GET['search'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM sanpham WHERE TenSanPham LIKE ?");
$stmt->execute(["%$search%"]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div id="topbar"></div>
    <div id="sidebar"></div>
    <div class="container mt-5" style="margin-top: 2rem ; margin-right: 4rem">
        <h1 class="text-center">Quản lý sản phẩm</h1>


        <!-- Tìm kiếm sản phẩm -->
        <form method="GET" class="form-inline mb-4">
            <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm sản phẩm"
                value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>

        <!-- Nút thêm sản phẩm -->
        <button class="btn btn-success mb-4" onclick="showAddForm()">Thêm sản phẩm</button>

        <!-- Danh sách sản phẩm -->
        <!-- <table class="table table-bordered"> -->
        <table class="table table-bordered table-striped">
            <!-- table-striped giúp sản phẩm hiển thị màu xen kẻ nhau-->
            <thead>
                <tr>
                    <th>Mã SP</th>
                    <th>Tên sản phẩm</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Hình ảnh</th>
                    <th>Ngày cập nhật</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['MaSanPham']; ?></td>
                    <td><?php echo htmlspecialchars($product['TenSanPham']); ?></td>
                    <td><?php echo htmlspecialchars($product['MoTa']); ?></td>
                    <td><?php echo $product['GiaBan']; ?></td>
                    <td>
                        <?php if ($product['HinhAnh']): ?>
                        <img src="<?php echo $product['HinhAnh']; ?>" alt="Hình ảnh sản phẩm"
                            style="width: 100px; height: auto;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $product['NgayCapNhat']; ?></td>
                    <td>
                        <!-- Nút sửa -->
                        <button class="btn btn-warning btn-sm" style="border:#3963dd; background:#cdeafb"
                            onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">Sửa</button>

                        <!-- Nút xóa -->
                        <!-- <form method="POST" style="display: inline;">
                        <input type="hidden" name="MaSanPham" value="<?php echo $product['MaSanPham']; ?>">
                        <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Xóa</button>
                    </form> -->
                        <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                            <input type="hidden" name="MaSanPham" value="<?php echo $product['MaSanPham']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Xóa</button>
                        </form>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Thêm/sửa sản phẩm -->
        <div id="product-form-container" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="form-title" class="modal-title">Thêm sản phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" id="product-form" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="MaSanPham" id="product-id">
                            <div class="form-group">
                                <label for="TenSanPham">Tên sản phẩm</label>
                                <input type="text" name="TenSanPham" id="TenSanPham" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="MoTa">Mô tả</label>
                                <textarea name="MoTa" id="MoTa" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="GiaBan">Giá</label>
                                <input type="number" step="0.01" name="GiaBan" id="GiaBan" class="form-control"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="HinhAnh">Hình ảnh</label>
                                <input type="file" name="HinhAnh" id="HinhAnh" class="form-control" accept="image/*">
                                <input type="hidden" name="existing_image" id="existing-image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_product" id="add-button"
                                class="btn btn-success">Thêm</button>
                            <button type="submit" name="update_product" id="update-button"
                                class="btn btn-primary d-none">Cập nhật</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
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