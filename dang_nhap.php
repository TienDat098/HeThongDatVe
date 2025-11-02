<?php session_start(); ?>
<?php

require_once 'core/db_connection.php';

// (Phần code chuyển hướng nếu đã đăng nhập giữ nguyên)
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) { header('Location: index.php'); exit; }
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) { header('Location: admin/dashboard.php'); exit; }

$error = '';
$success_msg = '';
if (isset($_GET['register']) && $_GET['register'] == 'success') { $success_msg = 'Đăng ký thành công! Vui lòng đăng nhập.'; }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password_input = $_POST['password']; // Mật khẩu người dùng nhập

    $stmt = $conn->prepare("SELECT * FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $password_db = $user['mat_khau']; // Mật khẩu lưu trong DB

        // === LOGIC SO SÁNH "THÔNG MINH" ===
        $is_password_correct = false;

        // 1. Kiểm tra xem mật khẩu trong DB có phải là HASH không
        if (str_starts_with($password_db, '$2y$')) {
            // Nếu là HASH, dùng password_verify
            if (password_verify($password_input, $password_db)) {
                $is_password_correct = true;
            }
        } else {
            // 2. Nếu không phải HASH, so sánh như mật khẩu thường
            if ($password_input === $password_db) {
                $is_password_correct = true;
            }
        }
        // === KẾT THÚC LOGIC SO SÁNH ===

        if ($is_password_correct) {
            // ĐĂNG NHẬP THÀNH CÔNG -> Kiểm tra vai trò
            if ($user['vai_tro'] == 'quan_tri') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['ho_ten'];
                header('Location: index.php');
                exit;
            }
        }
    }
    $error = 'Email hoặc mật khẩu không chính xác!';
}
require_once 'templates/header.php';
?>

<div class="container page-content">
    <div class="login-register-form">
        <h1>Đăng Nhập</h1>
        <p>Chào mừng bạn trở lại!</p>
        <?php if (!empty($error)): ?><p class="error-message"><?php echo $error; ?></p><?php endif; ?>
        <?php if (!empty($success_msg)): ?><p class="success-message"><?php echo $success_msg; ?></p><?php endif; ?>
        <form action="dang_nhap.php" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-switch" style="text-align: right; margin-top: -10px; margin-bottom: 20px;">
                <a href="quen_mat_khau.php">Quên mật khẩu?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
        </form>
        <p class="form-switch">
            Chưa có tài khoản? <a href="dang_ky.php">Đăng ký ngay</a>
        </p>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>