<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">

    <title>ADMIN</title>
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


    /* Sidebar styling */
    .sidebar {
        width: 300px;
        position: fixed;
        left: 0;
        top: 0;
        height: 100%;
        background-color: var(--main-color);
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

    .sidebar-brand {
        height: 90px;
        padding: 1rem 0 1rem 2rem;
        color: #275c2a;
    }

    .sidebar-brand span {
        display: inline-block;
        padding-right: 1rem;
    }

    .sidebar-menu {
        padding-top: 1rem;
        /* Khoảng cách trên menu */
    }

    .sidebar-menu li {
        width: 100%;
        margin-bottom: 1rem;
        /* Giảm khoảng cách giữa các mục */
        padding-left: 2rem;
    }

    .sidebar-menu a {
        padding: 0.8rem 1rem;
        /* Tăng khoảng cách bên trong */
        display: block;
        color: #000719;
        font-size: 1.2rem;
        border-radius: 30px 0px 0px 30px;
        /* Bo góc bên phải */
        transition: background-color 0.3s, color 0.3s;
        /* Hiệu ứng chuyển tiếp */
    }

    .sidebar-menu a:hover {
        background: #7bc45e;
        /* Màu nền khi hover */
        color: #fff;
        text-decoration: none;
    }

    .sidebar-menu a span:first-child {
        font-size: 1.5rem;
        padding-right: 1rem;
    }

    /* Admin header */
    .header {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
    }

    .admin-sidebar-top img {
        height: var(--top-height);
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #275c2a;
    }

    /* Admin content */
    .admin-content {
        margin-left: 300px;
        /* Khoảng cách bằng chiều rộng của sidebar */
        padding: 0px;
        background-color: #dee2e6;
        min-height: 100vh;
        border-radius: 20px;
    }

    .admin-content-main h1 {
        color: #dee2e6;
        font-size: 1.5rem;
        font-weight: bold;
        padding-left: 20px;
    }

    .admin-sidebar-top {
        padding-left: 30px;
    }
    </style>
</head>

<body style="font-size:15px;">
    <!-- Sidebar -->
    <div class="sidebar" style="width:auto">
        <div class="sidebar-brand">
            <div class="admin-sidebar-top">
                <img src="img/bglogo.png" alt="Logo">
            </div>
            <div class="sidebar-menu">
                <ul>
                    <li>
                        <a href="index.php?temp=danhmuc">
                            <div class="header-account">
                                Quản lý Danh Mục
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="index.php?temp=sanpham">
                            <div class="header-account">
                                Quản lý Sản Phẩm
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="index.php?temp=nhanvien">
                            <div class="header-account">
                                Quản lý Nhân Viên
                            </div>
                        </a>
                    </li>
                    <li>
                    <li>
                        <a href="index.php?temp=chatlieu">
                            <div class="header-account">
                                Quản lý Chất Liệu
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>