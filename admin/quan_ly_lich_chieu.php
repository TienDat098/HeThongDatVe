<?php
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// === PHẦN XỬ LÝ LOGIC (THÊM/SỬA) KHI FORM ĐƯỢC GỬI ĐI ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... (Toàn bộ code xử lý THÊM/SỬA lịch chiếu của bạn giữ nguyên ở đây) ...
    // ... (Mình rút gọn phần này) ...
    $id_phim = $_POST['id_phim'];
    $id_phong = $_POST['id_phong'];
    $thoi_gian_bat_dau_str = $_POST['ngay_chieu'] . ' ' . $_POST['gio_bat_dau'];
    $gia_ve = $_POST['gia_ve'];
    $id_lich_chieu = $_POST['id_lich_chieu'] ?? null;
    $stmt_phim = $conn->prepare("SELECT thoi_luong_phut FROM Phim WHERE id = ?");
    $stmt_phim->bind_param("i", $id_phim);
    $stmt_phim->execute();
    $thoi_luong = $stmt_phim->get_result()->fetch_assoc()['thoi_luong_phut'];
    $thoi_gian_bat_dau = new DateTime($thoi_gian_bat_dau_str);
    $thoi_gian_ket_thuc = clone $thoi_gian_bat_dau;
    $thoi_gian_ket_thuc->add(new DateInterval('PT' . $thoi_luong . 'M'));
    $thoi_gian_bat_dau_db = $thoi_gian_bat_dau->format('Y-m-d H:i:s');
    $thoi_gian_ket_thuc_db = $thoi_gian_ket_thuc->format('Y-m-d H:i:s');
    if ($id_lich_chieu) {
        $stmt = $conn->prepare("UPDATE LichChieu SET id_phim=?, id_phong=?, thoi_gian_bat_dau=?, thoi_gian_ket_thuc=?, gia_ve=? WHERE id=?");
        $stmt->bind_param("iissdi", $id_phim, $id_phong, $thoi_gian_bat_dau_db, $thoi_gian_ket_thuc_db, $gia_ve, $id_lich_chieu);
    } else {
        $stmt = $conn->prepare("INSERT INTO LichChieu (id_phim, id_phong, thoi_gian_bat_dau, thoi_gian_ket_thuc, gia_ve) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $id_phim, $id_phong, $thoi_gian_bat_dau_db, $thoi_gian_ket_thuc_db, $gia_ve);
    }
    $stmt->execute();
    header('Location: quan_ly_lich_chieu.php');
    exit;
}

// === PHẦN HIỂN THỊ GIAO DIỆN ===
$action = $_GET['action'] ?? 'list';
require_once 'templates/sidebar.php'; 
?>

<?php if ($action == 'add' || $action == 'edit'): ?>

    <?php
    // ... (Code của form THÊM/SỬA giữ nguyên như cũ) ...
    $lich_chieu = null; $ngay_chieu_val = ''; $gio_bat_dau_val = '';
    if ($action == 'edit') {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM LichChieu WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $lich_chieu = $stmt->get_result()->fetch_assoc();
        $ngay_chieu_val = date('Y-m-d', strtotime($lich_chieu['thoi_gian_bat_dau']));
        $gio_bat_dau_val = date('H:i', strtotime($lich_chieu['thoi_gian_bat_dau']));
    }
    ?>
    <div class="page-header">
        <h1><?php echo $action == 'add' ? 'Tạo Lịch Chiếu Mới' : 'Sửa Lịch Chiếu'; ?></h1>
    </div>
    <div class="page-content">
        <div class="card">
            <div class="form-container">
                <form action="quan_ly_lich_chieu.php" method="post">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id_lich_chieu" value="<?php echo $lich_chieu['id']; ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Chọn Phim</label>
                        <select name="id_phim" required>
                            <option value="">-- Chọn một bộ phim --</option>
                            <?php
                            $result_phim = $conn->query("SELECT id, tieu_de FROM Phim WHERE trang_thai != 'ngừng chiếu' ORDER BY tieu_de");
                            while ($phim = $result_phim->fetch_assoc()) {
                                $selected = ($lich_chieu['id_phim'] ?? '') == $phim['id'] ? 'selected' : '';
                                echo "<option value='{$phim['id']}' $selected>" . htmlspecialchars($phim['tieu_de']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Chọn Phòng Chiếu (Rạp)</label>
                        <select name="id_phong" required>
                            <option value="">-- Chọn phòng chiếu --</option>
                            <?php
                            $sql_phong = "SELECT p.id, p.ten_phong, r.ten_rap FROM PhongChieu p JOIN RapChieu r ON p.id_rap = r.id ORDER BY r.ten_rap, p.ten_phong";
                            $result_phong = $conn->query($sql_phong);
                            while ($phong = $result_phong->fetch_assoc()) {
                                $selected = ($lich_chieu['id_phong'] ?? '') == $phong['id'] ? 'selected' : '';
                                echo "<option value='{$phong['id']}' $selected>" . htmlspecialchars($phong['ten_rap'] . ' - ' . $phong['ten_phong']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ngày chiếu</label>
                        <input type="date" name="ngay_chieu" value="<?php echo $ngay_chieu_val; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Giờ bắt đầu</label>
                        <input type="time" name="gio_bat_dau" value="<?php echo $gio_bat_dau_val; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Giá vé (VNĐ)</label>
                        <input type="number" name="gia_ve" value="<?php echo $lich_chieu['gia_ve'] ?? '85000'; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu Lịch Chiếu</button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>

    <?php
    // --- BẮT ĐẦU LOGIC PHÂN TRANG CHO LỊCH CHIẾU ---
    $records_per_page = 10; // Hiển thị 10 lịch chiếu mỗi trang
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $records_per_page;

    // Lấy tổng số lịch chiếu
    $total_records = $conn->query("SELECT COUNT(id) AS total FROM LichChieu")->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);

    // Lấy dữ liệu lịch chiếu cho trang hiện tại
    $sql_list = "
        SELECT lc.id, p.tieu_de, r.ten_rap, pc.ten_phong, lc.thoi_gian_bat_dau, lc.thoi_gian_ket_thuc, lc.gia_ve 
        FROM LichChieu lc
        JOIN Phim p ON lc.id_phim = p.id
        JOIN PhongChieu pc ON lc.id_phong = pc.id
        JOIN RapChieu r ON pc.id_rap = r.id
        ORDER BY lc.thoi_gian_bat_dau DESC
        LIMIT ? OFFSET ?
    ";
    $stmt_list = $conn->prepare($sql_list);
    $stmt_list->bind_param("ii", $records_per_page, $offset);
    $stmt_list->execute();
    $result_list = $stmt_list->get_result();
    // --- KẾT THÚC LOGIC PHÂN TRANG ---
    ?>

    <div class="page-header">
        <h1>Quản lý Lịch chiếu</h1>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <a href="quan_ly_lich_chieu.php?action=add" class="btn btn-primary">Tạo Lịch Chiếu Mới</a>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th> <th>Tên Phim</th> <th>Rạp</th> <th>Phòng</th>
                            <th>Bắt đầu</th> <th>Kết thúc</th> <th>Giá vé</th> <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_list->num_rows > 0): ?>
                            <?php while($lich = $result_list->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $lich['id']; ?></td>
                                    <td><?php echo htmlspecialchars($lich['tieu_de']); ?></td>
                                    <td><?php echo htmlspecialchars($lich['ten_rap']); ?></td>
                                    <td><?php echo htmlspecialchars($lich['ten_phong']); ?></td>
                                    <td><?php echo date('H:i d/m/Y', strtotime($lich['thoi_gian_bat_dau'])); ?></td>
                                    <td><?php echo date('H:i d/m/Y', strtotime($lich['thoi_gian_ket_thuc'])); ?></td>
                                    <td><?php echo number_format($lich['gia_ve'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="quan_ly_lich_chieu.php?action=edit&id=<?php echo $lich['id']; ?>" class="btn btn-sm btn-edit">Sửa</a>
                                            <a href="lich_chieu_xoa.php?id=<?php echo $lich['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8">Chưa có lịch chiếu nào.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($current_page > 1): ?>
                        <a href="quan_ly_lich_chieu.php?page=<?php echo $current_page - 1; ?>">&laquo;</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="quan_ly_lich_chieu.php?page=<?php echo $i; ?>" class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages): ?>
                        <a href="quan_ly_lich_chieu.php?page=<?php echo $current_page + 1; ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            </div>
    </div>

<?php endif; ?>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>