<?php 
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// === BƯỚC 1: XỬ LÝ KHI FORM ĐƯỢC SUBMIT ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_phong = $_POST['id_phong'];
    $so_hang = (int)$_POST['so_hang']; // Ví dụ: 10 (sẽ là A, B, C... J)
    $so_ghe_moi_hang = (int)$_POST['so_ghe_moi_hang']; // Ví dụ: 12 (sẽ là 1, 2, 3... 12)
    $hang_vip_input = strtoupper($_POST['hang_vip']); // Ví dụ: "E, F, G"
    
    // Xử lý chuỗi hàng VIP thành một mảng
    // Ví dụ: "E, F, G" -> ['E', 'F', 'G']
    $hang_vip_array = array_map('trim', explode(',', $hang_vip_input));

    // --- RẤT QUAN TRỌNG: XÓA GHẾ CŨ (NẾU CÓ) ---
    // Để tránh tạo trùng lặp nếu chạy lại, ta xóa hết ghế cũ của phòng này
    $stmt_delete = $conn->prepare("DELETE FROM ghe WHERE id_phong = ?");
    $stmt_delete->bind_param("i", $id_phong);
    $stmt_delete->execute();
    $stmt_delete->close();

    // --- BẮT ĐẦU TẠO GHẾ HÀNG LOẠT ---
    // Chuẩn bị câu lệnh INSERT
    $stmt_insert = $conn->prepare("INSERT INTO ghe (id_phong, ma_ghe, loai_ghe) VALUES (?, ?, ?)");
    
    $tong_so_ghe = 0;
    
    // Vòng lặp HÀNG (A, B, C...)
    for ($i = 0; $i < $so_hang; $i++) {
        $ten_hang = chr(65 + $i); // 65 là mã ASCII của 'A'
        
        // Vòng lặp GHẾ (1, 2, 3...)
        for ($j = 1; $j <= $so_ghe_moi_hang; $j++) {
            $ma_ghe = $ten_hang . $j; // Ví dụ: A1, A2...
            
            // Kiểm tra xem hàng này có phải là hàng VIP không
            $loai_ghe = 'thuong';
            if (in_array($ten_hang, $hang_vip_array)) {
                $loai_ghe = 'vip';
            }
            
            // Thực thi INSERT
            $stmt_insert->bind_param("iss", $id_phong, $ma_ghe, $loai_ghe);
            $stmt_insert->execute();
            $tong_so_ghe++;
        }
    }
    
    $stmt_insert->close();
    
    // Cập nhật lại tổng số ghế trong bảng phongchieu
    $stmt_update_room = $conn->prepare("UPDATE phongchieu SET so_luong_ghe = ? WHERE id = ?");
    $stmt_update_room->bind_param("ii", $tong_so_ghe, $id_phong);
    $stmt_update_room->execute();
    $stmt_update_room->close();

    // Thông báo thành công và quay lại
    echo "<script>alert('Đã tạo thành công $tong_so_ghe ghế cho phòng!'); window.location.href = 'quan_ly_phong_chieu.php';</script>";
    exit;
}

// === BƯỚC 2: HIỂN THỊ FORM KHI CHƯA SUBMIT ===
// Lấy ID phòng từ URL (ví dụ: tao_ghe.php?id_phong=1)
if (!isset($_GET['id_phong']) || !is_numeric($_GET['id_phong'])) {
    echo "Lỗi: Không tìm thấy phòng.";
    exit;
}
$id_phong = $_GET['id_phong'];

// Lấy thông tin phòng để hiển thị
$stmt_phong = $conn->prepare("SELECT p.ten_phong, r.ten_rap FROM phongchieu p JOIN rapchieu r ON p.id_rap = r.id WHERE p.id = ?");
$stmt_phong->bind_param("i", $id_phong);
$stmt_phong->execute();
$phong = $stmt_phong->get_result()->fetch_assoc();
?>

<?php require_once 'templates/sidebar.php'; ?>

<div class="page-header">
    <h1>Tạo Ghế Hàng Loạt</h1>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header">
            Tạo ghế cho: <strong><?php echo htmlspecialchars($phong['ten_rap'] . ' - ' . $phong['ten_phong']); ?></strong>
        </div>
        <div class="form-container">
            <form action="tao_ghe.php" method="post">
                <input type="hidden" name="id_phong" value="<?php echo $id_phong; ?>">
                
                <div class="form-group">
                    <label>Tổng số hàng</label>
                    <input type="number" name="so_hang" placeholder="Ví dụ: 10 (Sẽ tạo ra hàng A, B, C... J)" required>
                </div>
                
                <div class="form-group">
                    <label>Số ghế trên mỗi hàng</label>
                    <input type="number" name="so_ghe_moi_hang" placeholder="Ví dụ: 12 (Sẽ tạo ra ghế 1, 2, 3... 12)" required>
                </div>

                <div class="form-group">
                    <label>Các hàng VIP (Phân cách bằng dấu phẩy)</label>
                    <input type="text" name="hang_vip" placeholder="Ví dụ: E, F, G (Để trống nếu không có)">
                </div>
                
                <button type="submit" class="btn btn-primary" onclick="return confirm('Hành động này sẽ XÓA TẤT CẢ ghế cũ (nếu có) và tạo lại ghế mới. Bạn có chắc chắn?');">Bắt đầu tạo ghế</button>
            </form>
        </div>
    </div>
</div>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>