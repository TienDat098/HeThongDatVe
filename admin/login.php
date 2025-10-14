<?php
session_start();
require_once '../core/db_connection.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SỬA LỖI TÊN BẢNG Ở ĐÂY: "NguoiDung" -> "nguoidung"
    $stmt = $conn->prepare("SELECT id, mat_khau FROM nguoidung WHERE email = ? AND vai_tro = 'quan_tri'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    var_dump($result->num_rows); 
    var_dump($conn->error);

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        // So sánh mật khẩu đã hash
        if (password_verify($password, $admin['mat_khau'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error_message = 'Email hoặc mật khẩu không chính xác!';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Quản trị</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h1>Admin Login</h1>
        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>