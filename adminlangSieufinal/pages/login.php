<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$database = "db_langque"; // Thay bằng tên database của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $database, 3366);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$error = ""; // Biến để lưu thông báo lỗi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sodienthoai = $_POST['sodienthoai'];
    $matkhau = $_POST['matkhau'];

    // Kiểm tra tài khoản trong cơ sở dữ liệu
    $sql = "SELECT * FROM taikhoan WHERE sodienthoai = ? AND matkhau = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $sodienthoai, $matkhau);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Đăng nhập thành công
        $row = $result->fetch_assoc();
        echo "<script>alert('Đăng nhập thành công! Chào mừng " . $row['hoten'] . "'); window.location.href = 'trangchu.php';</script>";
    } else {
        // Đăng nhập thất bại
        $error = "Số điện thoại hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        /* Thêm phần CSS như bạn đã viết trước */
        body {
            font-family: Arial, sans-serif;
            background: url('img/bglogin.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #fff;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            width: 400px;
            height: auto;
        }

        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-login {
            background-color: #cc0c5c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 50%;
            font-size: 16px;
            margin: 20px auto 0;
            display: block;
            text-align: center;
        }

        .btn-login:hover {
            background-color: #f46b8d;
        }

        .show-password {
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            justify-content: flex-end;
            margin: 20px auto 20px;
        }

        .show-password input {
            margin-right: -170px;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-password a {
            color: #f7aec3;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .register-link {
            color: #f7aec3;
            text-decoration: none;
            margin-left: 10px;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1>Đăng Nhập</h1>
        <?php if ($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="sodienthoai">Số Điện Thoại</label>
                <input type="text" id="sodienthoai" name="sodienthoai" required>
            </div>
            <div class="form-group">
                <label for="matkhau">Mật Khẩu</label>
                <input type="password" id="matkhau" name="matkhau" required>
                <div class="show-password">
                    <input type="checkbox" id="togglePassword">
                    <label for="togglePassword">Hiện mật khẩu</label>
                </div>
            </div>
            <button type="submit" class="btn-login">Đăng Nhập</button>
            <div class="forgot-password">
                <a href="pages/Quenmk.php">Quên mật khẩu?</a>
                <a href="pages/dangky.php" class="register-link">Đăng ký tài khoản</a>
            </div>
        </form>
    </div>

    <script>
        // Hiển thị hoặc ẩn mật khẩu
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("matkhau");

        togglePassword.addEventListener("change", function() {
            if (this.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    </script>
</body>

</html>