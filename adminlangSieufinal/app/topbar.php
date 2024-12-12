?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LANGQUE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    :root {
        --main-color: #f9fbff;
        --color-dark: #fcfdff;
        --text-grey: #275c2a;
        --top-height: 70px;
        /* Bạn có thể điều chỉnh chiều cao này */
    }

    /* Global styles */
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        list-style-type: none;
        text-decoration: none;
        font-family: Arial, sans-serif;
    }

    body {
        height: 100%;
        margin: 0;
        background-color: #f8f9fc;
        font-family: 'Roboto', sans-serif;
        font-size: 15px;
    }


    /* Topbar styling */
    .topbar {
        background-color: #00b207;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }

    .topbar .nav-link {
        color: #ffffff;
        transition: all 0.3s ease;
    }

    .topbar .nav-link:hover {
        color: #d1d3e2;
    }

    /* User profile */
    .img-profile {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .admin-content {
        padding: 20px;
    }

    .profile-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .modal-content {
        border-radius: 8px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #e3e6f0;
        background-color: #f8f9fc;
    }

    .modal-title {
        font-size: 1.5rem;
        color: #495057;
    }

    .modal-body {
        font-size: 1rem;
        color: #6c757d;
    }

    .modal-footer {
        border-top: 1px solid #ffffff;
        background-color: #ffffff;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }

    .btn-primary {
        background-color: #4fed59;
        border-color: #4fed59;
    }

    .btn-primary:hover {
        background-color: #4fed59;
        border-color: #4fed59;
    }
    </style>
</head>

<body>
    <div class="topbar">
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-white small"> Chào mừng
                            <?php echo $_SESSION['user']['ten'] ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="adminprofile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
                        </a>
                        <a class="dropdown-item" href="index.php?act=logout">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Bạn muốn đăng xuất?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Chọn "Đăng xuất" bên dưới nếu bạn đã sẵn sàng để kết thúc phiên làm việc hiện
                    tại.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <a class="btn btn-primary" href="pages/login.php">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>