<?php
require_once 'core/db_connection.php';
require_once 'templates/header.php';

$error = '';
$success = '';
$is_token_valid = false; // Biến kiểm tra token
$token = $_GET['token'] ?? '';

if (empty($token)) {
    $error = "Link không hợp lệ.";
} else {
    // === KIỂM TRA TOKEN ===
    $stmt = $conn->prepare("SELECT id, reset_token_expires_at FROM nguoidung WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = "Token không hợp lệ.";
    } else {
        $user = $result->fetch_assoc();
        
        // Kiểm tra xem token đã hết hạn chưa
        if (strtotime($user['reset_token_expires_at']) < time()) {
            $error = "Token đã hết hạn. Vui lòng yêu cầu link mới.";
        } else {
            // Token hợp lệ!
            $is_token_valid = true;
            $user_id = $user['id'];
        }
    }
}

// Xử lý khi người dùng gửi form mật khẩu mới
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_token_valid) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $posted_token = $_POST['token']; // Lấy token từ form ẩn

    // Kiểm tra lại token một lần nữa
    if ($posted_token !== $token) {
        $error = "Lỗi bảo mật. Vui lòng thử lại.";
    } elseif ($new_password !== $confirm_password) {
        $error = 'Mật khẩu mới không khớp!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        // Mọi thứ hợp lệ -> Mã hóa mật khẩu mới
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Cập nhật mật khẩu mới VÀ VÔ HIỆU HÓA TOKEN
        $stmt_update = $conn->prepare("UPDATE nguoidung SET mat_khau = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
        $stmt_update->bind_param("si", $new_hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            $success = 'Đã đặt lại mật khẩu thành công! Bạn có thể <a href="dang_nhap.php">Đăng nhập</a> ngay bây giờ.';
            $is_token_valid = false; // Ẩn form đi sau khi thành công
        } else {
            $error = 'Có lỗi xảy ra, vui lòng thử lại.';
        }
    }
}
?>

<div class="container page-content">
    <div class="login-register-form">
        <h1>Đặt Lại Mật Khẩu Mới</h1>
        
        <?php if (!empty($error)): ?><p class="error-message"><?php echo $error; ?></p><?php endif; ?>
        <?php if (!empty($success)): ?><p class="success-message"><?php echo $success; ?></p><?php endif; ?>

        <?php if ($is_token_valid && empty($success)): ?>
            <p>Vui lòng nhập mật khẩu mới của bạn.</p>
            <form action="dat_lai_mat_khau.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                 <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Đặt Lại Mật Khẩu</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>