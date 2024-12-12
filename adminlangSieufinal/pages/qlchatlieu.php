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

// Kiểm tra xem chất liệu có tồn tại không
function isMaterialExists($material_name, $pdo, $exclude_id = null) {
    $query = "SELECT COUNT(*) FROM chatlieu WHERE TenChatLieu = ?";
    $params = [$material_name];

    if ($exclude_id) {
        $query .= " AND MaChatLieu != ?";
        $params[] = $exclude_id;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
}

// Thêm chất liệu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_material'])) {
    $material_name = trim($_POST['TenChatLieu']);

    if (!empty($material_name)) {
        if (isMaterialExists($material_name, $pdo)) {
            $message = "Tên chất liệu đã tồn tại!";
        } else {
            $stmt = $pdo->prepare("INSERT INTO chatlieu (TenChatLieu) VALUES (?)");
            $stmt->execute([$material_name]);
            $message = "Thêm chất liệu thành công!";
        }
    } else {
        $message = "Tên chất liệu không được để trống!";
    }
}

// Tìm kiếm chất liệu
$search_term = '';
if (isset($_POST['search_material'])) {
    $search_term = trim($_POST['search_term']);
    $stmt = $pdo->prepare("SELECT * FROM chatlieu WHERE TenChatLieu LIKE ? ORDER BY MaChatLieu DESC");
    $stmt->execute(['%' . $search_term . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM chatlieu ORDER BY MaChatLieu DESC");
}

// Xóa chất liệu
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM chatlieu WHERE MaChatLieu = ?");
    $stmt->execute([$delete_id]);
    $message = "Xóa chất liệu thành công!";
}

// Cập nhật chất liệu
if (isset($_POST['edit_material'])) {
    $material_id = intval($_POST['MaChatLieu']);
    $material_name = trim($_POST['TenChatLieu']);

    if (!empty($material_name)) {
        if (isMaterialExists($material_name, $pdo, $material_id)) {
            $message = "Tên chất liệu đã tồn tại, vui lòng nhập lại!";
        } else {
            $stmt = $pdo->prepare("UPDATE chatlieu SET TenChatLieu = ? WHERE MaChatLieu = ?");
            $stmt->execute([$material_name, $material_id]);
            $message = "Cập nhật chất liệu thành công!";
        }
    } else {
        $message = "Tên chất liệu không được để trống!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Chất Liệu</title>
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
            <h1 class="text-center">Quản Lý Chất Liệu</h1>

            <!-- Hiển thị thông báo -->
            <?php if (isset($message)): ?>
                <div class="alert alert-info" id="message-alert"> <?php echo $message; ?> </div>
            <?php endif; ?>

            <!-- Form Tìm kiếm -->
            <form method="POST" action="" class="form-inline mb-4">
                <div class="form-group">
                    <input type="text" name="search_term" class="form-control" placeholder="Tìm kiếm chất liệu..." value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <button type="submit" name="search_material" class="btn btn-primary ml-2">Tìm kiếm</button>
            </form>

            <!-- Nút Thêm Chất Liệu -->
            <button class="btn btn-success mb-4" data-toggle="modal" data-target="#addMaterialModal">Thêm Chất Liệu</button>

            <!-- Bảng danh sách chất liệu -->
            <div class="card">
                <div class="card-header">Danh Sách Chất Liệu</div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Chất Liệu</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $row['MaChatLieu']; ?></td>
                                    <td><?php echo htmlspecialchars($row['TenChatLieu']); ?></td>
                                    <td>
                                        <!-- Nút Sửa Chất Liệu -->
                                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editMaterialModal" onclick="editMaterial(<?php echo $row['MaChatLieu']; ?>, '<?php echo htmlspecialchars($row['TenChatLieu']); ?>')">Sửa</button>
                                        <!-- Nút Xóa Chất Liệu -->
                                        <a href="?delete_id=<?php echo $row['MaChatLieu']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa chất liệu này không?');">Xóa</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Thêm Chất Liệu -->
        <div class="modal fade" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMaterialModalLabel">Thêm Chất Liệu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="TenChatLieu">Tên chất liệu:</label>
                                <input type="text" name="TenChatLieu" id="TenChatLieu" class="form-control" required>
                            </div>
                            <button type="submit" name="add_material" class="btn btn-success">Thêm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sửa Chất Liệu -->
        <div class="modal fade" id="editMaterialModal" tabindex="-1" role="dialog" aria-labelledby="editMaterialModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMaterialModalLabel">Sửa Chất Liệu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            <input type="hidden" name="MaChatLieu" id="editMaChatLieu">
                            <div class="form-group">
                                <label for="editTenChatLieu">Tên chất liệu:</label>
                                <input type="text" name="TenChatLieu" id="editTenChatLieu" class="form-control" required>
                            </div>
                            <button type="submit" name="edit_material" class="btn btn-success">Cập nhật</button>
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
            // Điền dữ liệu vào form sửa chất liệu
            function editMaterial(id, name) {
                $('#editMaChatLieu').val(id);
                $('#editTenChatLieu').val(name);
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
