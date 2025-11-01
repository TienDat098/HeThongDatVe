<?php 
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// Xử lý logic Thêm/Sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_phong = $_POST['ten_phong'];
    $id_rap = $_POST['id_rap'];
    $id_phong = $_POST['id_phong'] ?? null;
    // $so_luong_ghe = $_POST['so_luong_ghe']; // Tạm thời chưa dùng

    if ($id_phong) { // Cập nhật
        $stmt = $conn->prepare("UPDATE phongchieu SET ten_phong = ?, id_rap = ? WHERE id = ?");
        $stmt->bind_param("sii", $ten_phong, $id_rap, $id_phong);
    } else { // Thêm mới
        $stmt = $conn->prepare("INSERT INTO phongchieu (ten_phong, id_rap) VALUES (?, ?)");
        $stmt->bind_param("si", $ten_phong, $id_rap);
    }
    $stmt->execute();
    header('Location: quan_ly_phong_chieu.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
require_once 'templates/sidebar.php'; 
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
    <?php
    $phong = null;
    if ($action == 'edit') {
        $id = $_GET['id'];
        $stmt_edit = $conn->prepare("SELECT * FROM phongchieu WHERE id = ?");
        $stmt_edit->bind_param("i", $id);
        $stmt_edit->execute();
        $phong = $stmt_edit->get_result()->fetch_assoc();
    }
    // Lấy danh sách rạp chiếu để làm dropdown
    $ds_rap = $conn->query("SELECT id, ten_rap FROM rapchieu ORDER BY ten_rap");
    ?>
    <div class="page-header"><h1><?php echo $action == 'add' ? 'Thêm Phòng Mới' : 'Sửa Phòng Chiếu'; ?></h1></div>
    <div class="page-content"><div class="card"><div class="form-container">
        <form action="quan_ly_phong_chieu.php" method="post">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id_phong" value="<?php echo $phong['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Tên Phòng (Ví dụ: Phòng 01, IMAX...)</label>
                <input type="text" name="ten_phong" value="<?php echo htmlspecialchars($phong['ten_phong'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Thuộc Rạp Chiếu</label>
                <select name="id_rap" required>
                    <option value="">-- Chọn rạp chiếu --</option>
                    <?php while($rap = $ds_rap->fetch_assoc()): ?>
                        <option value="<?php echo $rap['id']; ?>" <?php echo ($phong['id_rap'] ?? '') == $rap['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($rap['ten_rap']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div></div></div>

<?php else: ?>
    <div class="page-header"><h1>Quản lý Phòng Chiếu</h1></div>
    <div class="page-content"><div class="card">
        <div class="card-header"><a href="quan_ly_phong_chieu.php?action=add" class="btn btn-primary">Thêm Phòng Mới</a></div>
        <div class="card-body">
            <table>
                <thead><tr><th>ID</th><th>Tên Phòng</th><th>Thuộc Rạp Chiếu</th><th>Hành động</th></tr></thead>
                <tbody>
                    <?php
                    $sql_list = "SELECT p.*, r.ten_rap FROM phongchieu p JOIN rapchieu r ON p.id_rap = r.id ORDER BY r.ten_rap, p.ten_phong";
                    $result = $conn->query($sql_list);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['ten_phong']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_rap']); ?></td>
                           <td>
                            <div class="action-buttons">
                                <a href="quan_ly_phong_chieu.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Sửa</a>
                                <a href="tao_ghe.php?id_phong=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Tạo Ghế</a>
                             </div>
                            </td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='4'>Chưa có phòng chiếu nào.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div></div>
<?php endif; ?>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>