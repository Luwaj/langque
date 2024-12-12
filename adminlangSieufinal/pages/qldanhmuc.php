<?php
// Bắt đầu phiên làm việc
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_langque"; // Thay thế bằng tên cơ sở dữ liệu của bạn

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Kiểm tra xem danh mục có tồn tại không
function isCategoryExists($category_name, $pdo, $exclude_id = null) {
    $query = "SELECT COUNT(*) FROM danhmucsanpham WHERE TenDanhMuc = ?";
    $params = [$category_name];

    if ($exclude_id) {
        $query .= " AND MaDanhMuc != ?";
        $params[] = $exclude_id;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

// Thêm danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['TenDanhMuc']);

    if (!empty($category_name)) {
        if (isCategoryExists($category_name, $pdo)) {
            $message = "Tên danh mục đã tồn tại!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO danhmucsanpham (TenDanhMuc) VALUES (?)");
            $stmt->execute([$category_name]);
            $message = "Thêm danh mục thành công!";
        }
    } else {
        $message = "Tên danh mục không được để trống!";
    }
}

// Tìm kiếm danh mục
$search_term = '';
if (isset($_POST['search_category'])) {
    $search_term = trim($_POST['search_term']);
    $stmt = $pdo->prepare("SELECT * FROM danhmucsanpham WHERE TenDanhMuc LIKE ? ORDER BY MaDanhMuc DESC");
    $stmt->execute(['%' . $search_term . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM danhmucsanpham ORDER BY MaDanhMuc DESC");
}

// Xóa danh mục
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM danhmucsanpham WHERE MaDanhMuc = ?");
    $stmt->execute([$delete_id]);
    $message = "Xóa danh mục thành công!";
}

// Cập nhật danh mục
if (isset($_POST['edit_category'])) {
    $category_id = intval($_POST['MaDanhMuc']);
    $category_name = trim($_POST['TenDanhMuc']);

    if (!empty($category_name)) {
        if (isCategoryExists($category_name, $pdo, $category_id)) {
            $message = "Tên danh mục đã tồn tại, vui lòng nhập lại!";
        } else {
            $stmt = $pdo->prepare("UPDATE danhmucsanpham SET TenDanhMuc = ? WHERE MaDanhMuc = ?");
            $stmt->execute([$category_name, $category_id]);
            $message = "Cập nhật danh mục thành công!";
        }
    } else {
        $message = "Tên danh mục không được để trống!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

</head>

<body>
<div id="topbar">
    </div>
    <div id="sidebar">
    </div>

    <!-- Content -->
    <div id="content">
        <div class="container mt-5 pt-5" style="margin-top: 2rem ; margin-right: 4rem">
            <h1 class="text-center">Quản Lý Danh Mục</h1>

            <!-- Hiển thị thông báo -->
            <?php if (isset($message)): ?>
                <div class="alert alert-info" id="message-alert"> <?php echo $message; ?> </div>
            <?php endif; ?>

            <!-- Form Tìm kiếm -->
            <form method="POST" action="" class="form-inline mb-4">
                <div class="form-group">
                    <input type="text" name="search_term" class="form-control" placeholder="Tìm kiếm danh mục..." value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <button type="submit" name="search_category" class="btn btn-primary ml-2">Tìm kiếm</button>
            </form>

            <!-- Nút Thêm Danh Mục -->
            <button class="btn btn-success mb-4" data-toggle="modal" data-target="#addCategoryModal">Thêm Danh Mục</button>

            <!-- Bảng danh sách danh mục -->
            <div class="card">
                <div class="card-header">Danh Sách Danh Mục</div>
                <div class="card-body">
                     <!-- table-striped giúp sản phẩm hiển thị màu xen kẻ nhau-->
                <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Danh Mục</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $row['MaDanhMuc']; ?></td>
                                    <td><?php echo htmlspecialchars($row['TenDanhMuc']); ?></td>
                                    <td>
                                        <!-- Nút Sửa Danh Mục -->
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editCategoryModal" onclick="editCategory(<?php echo $row['MaDanhMuc']; ?>, '<?php echo htmlspecialchars($row['TenDanhMuc']); ?>')">Sửa</button>
                                        <!-- Nút Xóa Danh Mục -->
                                        <a href="?delete_id=<?php echo $row['MaDanhMuc']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Danh Mục -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Thêm Danh Mục</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="TenDanhMuc">Tên danh mục:</label>
                                <input type="text" name="TenDanhMuc" id="TenDanhMuc" class="form-control" required>
                            </div>
                            <button type="submit" name="add_category" class="btn btn-success">Thêm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sửa Danh Mục -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Sửa Danh Mục</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <input type="hidden" name="MaDanhMuc" id="editMaDanhMuc">
                            <div class="form-group">
                                <label for="editTenDanhMuc">Tên danh mục:</label>
                                <input type="text" name="TenDanhMuc" id="editTenDanhMuc" class="form-control" required>
                            </div>
                            <button type="submit" name="edit_category" class="btn btn-success">Cập nhật</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <script>
              function loadHTML(url, elementId) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById(elementId).innerHTML = data;
            })
            .catch(error => console.error('Error loading HTML:', error));
    }
            // Điền dữ liệu vào form sửa danh mục
            function editCategory(id, name) {
                $('#editMaDanhMuc').val(id);
                $('#editTenDanhMuc').val(name);
            }

            // Tự động ẩn thông báo sau 3 giây
            $(document).ready(function() {
                if ($('#message-alert').length) {
                    setTimeout(function() {
                        $('#message-alert').fadeOut();
                    }, 3000); // Ẩn sau 3 giây
                }
            });
            loadHTML('app/topbar.php', 'topbar');
            loadHTML('app/sidebar.php', 'sidebar');
        </script>
    </div>
</body>

</html>

<?php
// Đóng kết nối
$pdo = null;
?>
