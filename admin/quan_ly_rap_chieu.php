<?php 
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// === PHẦN XỬ LÝ LOGIC (THÊM/SỬA) KHI FORM ĐƯỢC GỬI ĐI ===
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_rap = $_POST['ten_rap'];
    $dia_chi = $_POST['dia_chi'];
    $thanh_pho = $_POST['thanh_pho'];
    $id_cum_rap = $_POST['id_cum_rap'];
    $id_rap = $_POST['id_rap'] ?? null;

    if ($id_rap) { // Cập nhật
        $stmt = $conn->prepare("UPDATE rapchieu SET ten_rap=?, dia_chi=?, thanh_pho=?, id_cum_rap=? WHERE id=?");
        $stmt->bind_param("sssii", $ten_rap, $dia_chi, $thanh_pho, $id_cum_rap, $id_rap);
    } else { // Thêm mới
        $stmt = $conn->prepare("INSERT INTO rapchieu (ten_rap, dia_chi, thanh_pho, id_cum_rap) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $ten_rap, $dia_chi, $thanh_pho, $id_cum_rap);
    }
    $stmt->execute();
    header('Location: quan_ly_rap_chieu.php');
    exit;
}

// === PHẦN HIỂN THỊ GIAO DIỆN ===
$action = $_GET['action'] ?? 'list';
require_once 'templates/sidebar.php'; 
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
    <?php
    $rap = null;
    if ($action == 'edit') {
        $id = $_GET['id'];
        $stmt_edit = $conn->prepare("SELECT * FROM rapchieu WHERE id = ?");
        $stmt_edit->bind_param("i", $id);
        $stmt_edit->execute();
        $rap = $stmt_edit->get_result()->fetch_assoc();
    }
    // Lấy danh sách cụm rạp để làm dropdown
    $ds_cum_rap = $conn->query("SELECT id, ten_cum_rap FROM cumrap ORDER BY ten_cum_rap");
    ?>
    <div class="page-header"><h1><?php echo $action == 'add' ? 'Thêm Rạp Mới' : 'Sửa Rạp Chiếu'; ?></h1></div>
    <div class="page-content"><div class="card"><div class="form-container">
        <form action="quan_ly_rap_chieu.php" method="post">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id_rap" value="<?php echo $rap['id']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Tên Rạp Chiếu</label>
                <input type="text" name="ten_rap" value="<?php echo htmlspecialchars($rap['ten_rap'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Địa chỉ</label>
                <input type="text" name="dia_chi" value="<?php echo htmlspecialchars($rap['dia_chi'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Thành phố</label>
                <input type="text" name="thanh_pho" value="<?php echo htmlspecialchars($rap['thanh_pho'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Thuộc Cụm Rạp (Thương hiệu)</label>
                <select name="id_cum_rap" required>
                    <option value="">-- Chọn thương hiệu --</option>
                    <?php while($cum = $ds_cum_rap->fetch_assoc()): ?>
                        <option value="<?php echo $cum['id']; ?>" <?php echo ($rap['id_cum_rap'] ?? '') == $cum['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cum['ten_cum_rap']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div></div></div>

<?php else: ?>
    <?php
    // --- BẮT ĐẦU LOGIC PHÂN TRANG ---
    $records_per_page = 10; // 10 rạp mỗi trang
    $current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $records_per_page;

    // Lấy tổng số rạp
    $total_records = $conn->query("SELECT COUNT(id) AS total FROM rapchieu")->fetch_assoc()['total'];
    $total_pages = ceil($total_records / $records_per_page);

    // Lấy dữ liệu rạp cho trang hiện tại
    $sql_list = "
        SELECT r.id, r.ten_rap, r.dia_chi, r.thanh_pho, c.ten_cum_rap 
        FROM rapchieu r 
        LEFT JOIN cumrap c ON r.id_cum_rap = c.id 
        ORDER BY r.thanh_pho, c.ten_cum_rap, r.ten_rap 
        LIMIT ? OFFSET ?
    ";
    $stmt_list = $conn->prepare($sql_list);
    $stmt_list->bind_param("ii", $records_per_page, $offset);
    $stmt_list->execute();
    $result = $stmt_list->get_result();
    // --- KẾT THÚC LOGIC PHÂN TRANG ---
    ?>

    <div class="page-header"><h1>Quản lý Rạp Chiếu</h1></div>
    <div class="page-content"><div class="card">
        <div class="card-header"><a href="quan_ly_rap_chieu.php?action=add" class="btn btn-primary">Thêm Rạp Mới</a></div>
        <div class="card-body">
            <table>
                <thead><tr><th>ID</th><th>Tên Rạp</th><th>Địa chỉ</th><th>Thành phố</th><th>Cụm Rạp</th><th>Hành động</th></tr></thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['ten_rap']); ?></td>
                            <td><?php echo htmlspecialchars($row['dia_chi']); ?></td>
                            <td><?php echo htmlspecialchars($row['thanh_pho']); ?></td>
                            <td><?php echo htmlspecialchars($row['ten_cum_rap']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="quan_ly_rap_chieu.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Sửa</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan='6'>Chưa có rạp chiếu nào.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="quan_ly_rap_chieu.php?page=<?php echo $current_page - 1; ?>">&laquo;</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="quan_ly_rap_chieu.php?page=<?php echo $i; ?>" class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <?php if ($current_page < $total_pages): ?>
                    <a href="quan_ly_rap_chieu.php?page=<?php echo $current_page + 1; ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        </div></div>
<?php endif; ?>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>