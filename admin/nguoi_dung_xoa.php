<?php
require_once '../core/db_connection.php';
session_start();

// Kiểm tra đăng nhập admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Bảo vệ, không cho phép admin tự xóa chính mình
    if ($id == $_SESSION['admin_id']) {
        // Có thể thêm thông báo lỗi ở đây
        header('Location: quan_ly_nguoi_dung.php');
        exit;
    }

    // Dùng prepared statement để xóa an toàn
    $stmt = $conn->prepare("DELETE FROM nguoidung WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Quay lại trang danh sách
header('Location: quan_ly_nguoi_dung.php');
exit;
?>