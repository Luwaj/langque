<div class="py-5">
    <?php
  // include("./app/topbar.php");
  // include("./app/sidebar.php");
session_start();

  if (isset($_GET['temp'])) {
    $page = $_GET['temp'];
    switch ($_GET['temp']) {
      case 'sanpham':
        include("pages/qlsanpham.php");
        break;
      case 'danhmuc':
        include("pages/qldanhmuc.php");
        break;
      case 'nhanvien':
        include("pages/qlnhanvien.php");
        break;
      case 'chatlieu':
        include("pages/qlchatlieu.php");
        break;
    }
  } else
    // include("pages/login.php"); //trang mặc định
    include("pages/qlsanpham.php");

  ?>
</div>