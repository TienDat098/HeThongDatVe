<?php 
require_once 'templates/header.php';
require_once '../core/db_connection.php';



/* PHẦN XỬ LÝ LOGIC (THÊM/SỬA) KHI FORM ĐƯỢC GỬI ĐI */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tieu_de = $_POST['tieu_de'] ?? '';
    $mo_ta = $_POST['mo_ta'] ?? '';
    $thoi_luong = $_POST['thoi_luong_phut'] ?? 0;
    $ngay_khoi_chieu = $_POST['ngay_khoi_chieu'] ?? null;
    $trailer = $_POST['trailer'] ?? '';
    $ngon_ngu = $_POST['ngon_ngu'] ?? '';
    $trang_thai = $_POST['trang_thai'] ?? 'sắp chiếu';
    $id_phim = $_POST['id_phim'] ?? null; 
    $poster_name = $_POST['current_poster'] ?? ''; 
    // Xử lý upload poster mới
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $target_dir = "../assets/images/posters/";
        // Xóa poster cũ nếu có
        if (!empty($poster_name) && file_exists($target_dir . $poster_name)) {
            unlink($target_dir . $poster_name);
        }
        $poster_name = time() . '_' . basename($_FILES["poster"]["name"]);
        move_uploaded_file($_FILES["poster"]["tmp_name"], $target_dir . $poster_name);
    }

    if ($id_phim) { // Nếu có ID -> Cập nhật (UPDATE)
        $stmt = $conn->prepare("UPDATE Phim SET tieu_de=?, mo_ta=?, thoi_luong_phut=?, ngay_khoi_chieu=?, poster=?, trailer=?, ngon_ngu=?, trang_thai=? WHERE id=?");
        $stmt->bind_param("ssisssssi", $tieu_de, $mo_ta, $thoi_luong, $ngay_khoi_chieu, $poster_name, $trailer, $ngon_ngu, $trang_thai, $id_phim);
    } else { // Nếu không có ID -> Thêm mới (INSERT)
        $stmt = $conn->prepare("INSERT INTO Phim (tieu_de, mo_ta, thoi_luong_phut, ngay_khoi_chieu, poster, trailer, ngon_ngu, trang_thai) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisssss", $tieu_de, $mo_ta, $thoi_luong, $ngay_khoi_chieu, $poster_name, $trailer, $ngon_ngu, $trang_thai);
    }
    
    $stmt->execute();
    header('Location: quan_ly_phim.php');
    exit;
}

// === PHẦN QUYẾT ĐỊNH HIỂN THỊ GIAO DIỆN NÀO ===
// Lấy action từ URL, mặc định là 'list' (hiển thị danh sách)
$action = $_GET['action'] ?? 'list';

require_once 'templates/sidebar.php'; 
?>

<?php if ($action == 'add' || $action == 'edit'): ?>

    <?php
    $phim = null;
    if ($action == 'edit') {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM Phim WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $phim = $result->fetch_assoc();
    }
    ?>

    <div class="page-header">
        <h1><?php echo $action == 'add' ? 'Thêm Phim Mới' : 'Sửa Thông Tin Phim'; ?></h1>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="form-container">
                <form action="quan_ly_phim.php" method="post" enctype="multipart/form-data">
                    <?php if ($action == 'edit'): ?>
                        <input type="hidden" name="id_phim" value="<?php echo $phim['id']; ?>">
                        <input type="hidden" name="current_poster" value="<?php echo $phim['poster']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Tiêu đề phim</label>
                        <input type="text" name="tieu_de" value="<?php echo htmlspecialchars($phim['tieu_de'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="mo_ta"><?php echo htmlspecialchars($phim['mo_ta'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Thời lượng (phút)</label>
                        <input type="number" name="thoi_luong_phut" value="<?php echo $phim['thoi_luong_phut'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Ngày khởi chiếu</label>
                        <input type="date" name="ngay_khoi_chieu" value="<?php echo $phim['ngay_khoi_chieu'] ?? ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Poster</label>
                        <input type="file" name="poster" accept="image/*">
                        <?php if ($action == 'edit' && !empty($phim['poster'])): ?>
                            <img src="../assets/images/posters/<?php echo $phim['poster']; ?>" class="current-poster">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Link Trailer (YouTube)</label>
                        <input type="text" name="trailer" value="<?php echo htmlspecialchars($phim['trailer'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Ngôn ngữ</label>
                        <input type="text" name="ngon_ngu" value="<?php echo htmlspecialchars($phim['ngon_ngu'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select name="trang_thai">
                         <option value="sắp chiếu" <?php echo ($phim['trang_thai'] ?? '') == 'sắp chiếu' ? 'selected' : ''; ?>>Sắp chiếu</option>
                          <option value="đang chiếu" <?php echo ($phim['trang_thai'] ?? '') == 'đang chiếu' ? 'selected' : ''; ?>>Đang chiếu</option>
                            <option value="ngừng chiếu" <?php echo ($phim['trang_thai'] ?? '') == 'ngừng chiếu' ? 'selected' : ''; ?>>Ngừng chiếu</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </form>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="page-header">
        <h1>Quản lý Phim</h1>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-header">
                <a href="quan_ly_phim.php?action=add" class="btn btn-primary">Thêm Phim Mới</a>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Poster</th>
                            <th>Tiêu đề</th>
                            <th>Thời lượng</th>
                            <th>Khởi chiếu</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, poster, tieu_de, thoi_luong_phut, ngay_khoi_chieu, trang_thai FROM Phim ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($phim = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $phim['id']; ?></td>
                                <td>
                                    <?php if (!empty($phim['poster']) && file_exists("../assets/images/posters/" . $phim['poster'])): ?>
                                        <img src="../assets/images/posters/<?php echo htmlspecialchars($phim['poster']); ?>" alt="poster">
                                    <?php else: ?>
                                        <span>No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($phim['tieu_de']); ?></td>
                                <td><?php echo $phim['thoi_luong_phut']; ?> phút</td>
                                <td><?php echo date('d/m/Y', strtotime($phim['ngay_khoi_chieu'])); ?></td>
                                <td><?php echo $phim['trang_thai']; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="quan_ly_phim.php?action=edit&id=<?php echo $phim['id']; ?>" class="btn btn-sm btn-edit">Sửa</a>
                                        <a href="phim_xoa.php?id=<?php echo $phim['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>Chưa có phim nào.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>


<?php 
require_once 'templates/footer.php';
$conn->close();
?>