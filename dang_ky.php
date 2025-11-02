<?php session_start(); ?>
<?php
require_once 'core/db_connection.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Lấy mật khẩu chữ thường
    $so_dien_thoai = $_POST['so_dien_thoai'];

    $stmt_check = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = 'Email này đã được sử dụng. Vui lòng chọn email khác.';
    } else {
        // === SỬA LỖI Ở ĐÂY: LUÔN LUÔN MÃ HÓA MẬT KHẨU MỚI ===
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt_insert = $conn->prepare("INSERT INTO nguoidung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro) VALUES (?, ?, ?, ?, 'nguoi_dung')");
        // Lưu mật khẩu đã mã hóa vào DB
        $stmt_insert->bind_param("ssss", $ho_ten, $email, $hashed_password, $so_dien_thoai); 
        
        if ($stmt_insert->execute()) {
            header('Location: dang_nhap.php?register=success');
            exit;
        } else {
            $error = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
        }
    }
}
require_once 'templates/header.php';
?>

<div class="container page-content">
    <div class="login-register-form">
        <h1>Đăng Ký Tài Khoản</h1>
        <p>Tạo tài khoản để bắt đầu đặt vé ngay!</p>
        <?php if (!empty($error)): ?><p class="error-message"><?php echo $error; ?></p><?php endif; ?>
        <form action="dang_ky.php" method="post">
            <div class="form-group">
                <label for="ho_ten">Họ và tên</label>
                <input type="text" id="ho_ten" name="ho_ten" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
             <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="text" id="so_dien_thoai" name="so_dien_thoai">
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
        </form>
        <p class="form-switch">
            Đã có tài khoản? <a href="dang_nhap.php">Đăng nhập ngay</a>
        </p>
    </div>
</div>
<?php require_once 'templates/footer.php'; ?>