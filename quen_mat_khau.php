<?php
require_once 'core/db_connection.php';
require_once 'templates/header.php';

$error = '';
$success = '';
$reset_link = ''; // Biến để lưu link (giả lập gửi mail)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM nguoidung WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $error = 'Email không tồn tại trong hệ thống.';
    } else {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        // === BẮT ĐẦU TẠO TOKEN BẢO MẬT ===
        
        // 1. Tạo một token ngẫu nhiên (ví dụ: chuỗi 64 ký tự)
        $token = bin2hex(random_bytes(32));
        
        // 2. Đặt thời gian hết hạn (ví dụ: 1 giờ kể từ bây giờ)
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 HOUR'));

        // 3. Lưu token và thời gian hết hạn vào database
        $stmt_update = $conn->prepare("UPDATE nguoidung SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?");
        $stmt_update->bind_param("ssi", $token, $expires_at, $user_id);
        $stmt_update->execute();

        // 4. TẠO LINK ĐẶT LẠI MẬT KHẨU
        // (Đây là link đáng lẽ sẽ được gửi qua email)
        $reset_link = BASE_URL . "dat_lai_mat_khau.php?token=" . $token;
        
        $success = 'Link đặt lại mật khẩu đã được tạo (giả lập gửi mail). Vui lòng bấm vào link bên dưới để tiếp tục.';
    }
}
?>

<div class="container page-content">
    <div class="login-register-form">
        <h1>Quên Mật Khẩu</h1>
        <p>Vui lòng nhập email của bạn. Chúng tôi sẽ tạo một link để đặt lại mật khẩu.</p>
        
        <?php if (!empty($error)): ?><p class="error-message"><?php echo $error; ?></p><?php endif; ?>
        <?php if (!empty($success)): ?><p class="success-message"><?php echo $success; ?></p><?php endif; ?>

        <?php if (!empty($reset_link)): ?>
            <div class="form-group" style="text-align: center; word-wrap: break-word; background: #f0f0f0; padding: 15px; border-radius: 5px;">
                <p style="color: #333; margin:0;">Bấm vào link này để đặt lại mật khẩu:</p>
                <a href="<?php echo $reset_link; ?>" style="font-weight: bold;"><?php echo $reset_link; ?></a>
            </div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
            <form action="quen_mat_khau.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Gửi hướng dẫn</button>
            </form>
        <?php endif; ?>

         <p class="form-switch" style="margin-top: 20px;">
            Nhớ ra rồi? <a href="dang_nhap.php">Quay lại Đăng nhập</a>
        </p>
    </div>
</div>

<?php require_once 'templates/footer.php'; ?>