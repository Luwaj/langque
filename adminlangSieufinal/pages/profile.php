<?php
session_start();
include('config/db.php'); // Kết nối cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_SESSION['admin_id']; // Lấy ID admin từ session
    $adminName = trim($_POST['adminName']);
    $adminEmail = trim($_POST['adminEmail']);
    $adminPassword = !empty($_POST['adminPassword']) ? password_hash($_POST['adminPassword'], PASSWORD_BCRYPT) : null;

    $avatarPath = null;
    if (isset($_FILES['adminAvatar']) && $_FILES['adminAvatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $avatarPath = $uploadDir . basename($_FILES['adminAvatar']['name']);
        if (!move_uploaded_file($_FILES['adminAvatar']['tmp_name'], $avatarPath)) {
            die('Không thể upload ảnh.');
        }
    }

    // Cập nhật thông tin vào cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE admins SET name = ?, email = ?, password = IFNULL(?, password), avatar = IFNULL(?, avatar) WHERE id = ?");
    $stmt->bind_param("ssssi", $adminName, $adminEmail, $adminPassword, $avatarPath, $adminId);

    if ($stmt->execute()) {
        echo "Thông tin đã được cập nhật thành công!";
    } else {
        echo "Đã xảy ra lỗi khi cập nhật thông tin.";
    }
    $stmt->close();
    $conn->close();
}
?>
