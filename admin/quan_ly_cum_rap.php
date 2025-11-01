<?php 
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// Xử lý logic Thêm/Sửa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_cum_rap = $_POST['ten_cum_rap'];
    $id_cum_rap = $_POST['id_cum_rap'] ?? null;
    
    // Xử lý upload logo
    $logo_name = $_POST['current_logo'] ?? '';
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../assets/images/logos/";
        $logo_name = time() . '_' . basename($_FILES["logo"]["name"]);
        move_uploaded_file($_FILES["logo"]["tmp_name"], $target_dir . $logo_name);
    }

    if ($id_cum_rap) { // Cập nhật
        $stmt = $conn->prepare("UPDATE cumrap SET ten_cum_rap = ?, logo = ? WHERE id = ?");
        $stmt->bind_param("ssi", $ten_cum_rap, $logo_name, $id_cum_rap);
    } else { // Thêm mới
        $stmt = $conn->prepare("INSERT INTO cumrap (ten_cum_rap, logo) VALUES (?, ?)");
        $stmt->bind_param("ss", $ten_cum_rap, $logo_name);
    }
    $stmt->execute();
    header('Location: quan_ly_cum_rap.php');
    exit;
}

$action = $_GET['action'] ?? 'list';
require_once 'templates/sidebar.php'; 
?>

<?php if ($action == 'add' || $action == 'edit'): ?>
    <?php
    $cum_rap = null;
    if ($action == 'edit') {
        $id = $_GET['id'];
        $stmt_edit = $conn->prepare("SELECT * FROM cumrap WHERE id = ?");
        $stmt_edit->bind_param("i", $id);
        $stmt_edit->execute();
        $cum_rap = $stmt_edit->get_result()->fetch_assoc();
    }
    ?>
    <div class="page-header"><h1><?php echo $action == 'add' ? 'Thêm Cụm Rạp Mới' : 'Sửa Cụm Rạp'; ?></h1></div>
    <div class="page-content"><div class="card"><div class="form-container">
        <form action="quan_ly_cum_rap.php" method="post" enctype="multipart/form-data">
            <?php if ($action == 'edit'): ?>
                <input type="hidden" name="id_cum_rap" value="<?php echo $cum_rap['id']; ?>">
                <input type="hidden" name="current_logo" value="<?php echo $cum_rap['logo']; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label>Tên Cụm Rạp (Thương hiệu)</label>
                <input type="text" name="ten_cum_rap" value="<?php echo htmlspecialchars($cum_rap['ten_cum_rap'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" accept="image/*">
                <?php if ($action == 'edit' && !empty($cum_rap['logo'])): ?>
                    <img src="../assets/images/logos/<?php echo $cum_rap['logo']; ?>" class="current-poster" style="max-width: 100px; height: auto; background: #555; padding: 5px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div></div></div>

<?php else: ?>
    <div class="page-header"><h1>Quản lý Cụm Rạp</h1></div>
    <div class="page-content"><div class="card">
        <div class="card-header"><a href="quan_ly_cum_rap.php?action=add" class="btn btn-primary">Thêm Cụm Rạp Mới</a></div>
        <div class="card-body">
            <table>
                <thead><tr><th>ID</th><th>Logo</th><th>Tên Cụm Rạp</th><th>Hành động</th></tr></thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM cumrap ORDER BY ten_cum_rap ASC");
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td>
                                <?php if (!empty($row['logo'])): ?>
                                    <img src="../assets/images/logos/<?php echo htmlspecialchars($row['logo']); ?>" alt="logo" style="width: 80px; height: auto; background: #555; padding: 5px; border-radius: 5px;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['ten_cum_rap']); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="quan_ly_cum_rap.php?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-edit">Sửa</a>
                                    </div>
                            </td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='4'>Chưa có cụm rạp nào.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div></div>
<?php endif; ?>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>