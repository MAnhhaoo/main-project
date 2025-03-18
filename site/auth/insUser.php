<?php
// Kết nối MySQL
$conn = new mysqli("localhost", "root", "", "duan1_database");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem dữ liệu có được gửi từ form không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra dữ liệu đầu vào
    if (!isset($_POST['ho_ten'], $_POST['mat_khau'], $_POST['diachi'], $_POST['sodienthoai'], $_POST['email'])) {
        die("Lỗi: Thiếu dữ liệu đầu vào.");
    }

    // Nhận dữ liệu từ form
    $ho_ten = trim($_POST['ho_ten']);
    $mat_khau = md5($_POST['mat_khau']);
// Mã hóa mật khẩu
    $diachi = trim($_POST['diachi']);
    $sodienthoai = trim($_POST['sodienthoai']);
    $email = trim($_POST['email']);

    // Chuẩn bị truy vấn SQL
    $sql = "INSERT INTO tbl_nguoidung (ho_ten, mat_khau, diachi, sodienthoai, email) VALUES (?, ?, ?, ?, ?)";
    $avatar = null;
	//var_dump($_FILES);
    if (!empty($_FILES["avatar"]["name"])) {		
        $target_dir = "../../uploads/"; // Thư mục lưu ảnh
        $avatar = $target_dir . basename($_FILES["avatar"]["name"]);
        move_uploaded_file($_FILES["avatar"]["tmp_name"], $avatar);
    }
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $ho_ten, $mat_khau, $diachi, $sodienthoai, $email);

        // Thực thi truy vấn
        if ($stmt->execute()) {
            // Đăng ký thành công, chuyển hướng sang trang đăng nhập
            header("Location: login.php");
            exit(); // Dừng script để tránh chạy tiếp các dòng code khác
        } else {
            echo "Lỗi khi tạo tài khoản: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Lỗi SQL: " . $conn->error;
    }
}




$conn->close();
?>



<?php
ob_start();
session_start();
include "../models/connectdb.php";
include "../models/user.php";
$FOLDER_VAR = "/PRO1014_DA1/main-project";
$ROOT_URL = $_SERVER['DOCUMENT_ROOT'] . "$FOLDER_VAR";

include "$ROOT_URL/global.php";
include "$ROOT_URL/pdo-library.php";
include "$ROOT_URL/DAO/user.php";
// var_dump($_SESSION);
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = [];
}

if (!isset($_SESSION['toastAlert'])) {
    $_SESSION['toastAlert'] = "";
}

?>

