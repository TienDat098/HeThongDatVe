<?php
require_once 'core/db_connection.php';
require_once 'templates/header.php'; // Header sẽ tự động gọi session_start()

// Bắt buộc người dùng phải đăng nhập mới vào được trang này
if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header('Location: dang_nhap.php');
    exit;
}

// Lấy ID của người đang đăng nhập
$user_id = $_SESSION['user_id'] ?? $_SESSION['admin_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Lấy mật khẩu hiện tại từ DB
    $stmt = $conn->prepare("SELECT mat_khau FROM nguoidung WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $password_db = $stmt->get_result()->fetch_assoc()['mat_khau'];

    // 2. Xác thực mật khẩu cũ (dùng logic "thông minh" giống trang login)
    $is_old_pass_valid = false;
    if (str_starts_with($password_db, '$2y$')) {
        $is_old_pass_valid = password_verify($old_password, $password_db);
    } else {
        $is_old_pass_valid = ($old_password === $password_db);
    }

    if (!$is_old_pass_valid) {
        $error = 'Mật khẩu cũ không chính xác!';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Mật khẩu mới không khớp!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
    } else {
        // 3. Mọi thứ hợp lệ -> Mã hóa mật khẩu mới
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 4. Cập nhật mật khẩu mới vào DB
        $stmt_update = $conn->prepare("UPDATE nguoidung SET mat_khau = ? WHERE id = ?");
        $stmt_update->bind_param("si", $new_hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            $success = 'Đổi mật khẩu thành công!';
        } else {
            $error = 'Có lỗi xảy ra, vui lòng thử lại.';
        }
    }
}
?>

<div class="container page-content">
    <div class="login-register-form">
        <h1>Đổi Mật Khẩu</h1>
        
        <?php if (!empty($error)): ?><p class="error-message"><?php echo $error; ?></p><?php endif; ?>
        <?php if (!empty($success)): ?><p class="success-message"><?php echo $success; ?></p><?php endif; ?>

        <form action="doi_mat_khau.php" method="post">
            <div class="form-group">
                <label for="old_password">Mật khẩu cũ</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
             <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Cập Nhật Mật Khẩu</button>
        </form>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>