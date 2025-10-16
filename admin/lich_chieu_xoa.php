<?php
require_once '../core/db_connection.php';
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Dùng prepared statement để xóa an toàn
    $stmt = $conn->prepare("DELETE FROM LichChieu WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Quay lại trang danh sách
header('Location: quan_ly_lich_chieu.php');
exit;
?>