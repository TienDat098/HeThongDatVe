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
    
    // Dùng prepared statement để cập nhật trạng thái an toàn
    $stmt = $conn->prepare("UPDATE DatVe SET trang_thai = 'da_huy' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Quay lại trang danh sách
header('Location: quan_ly_dat_ve.php');
exit;
?>