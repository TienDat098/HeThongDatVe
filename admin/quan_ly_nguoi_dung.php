<?php 

require_once '../core/db_connection.php';
require_once 'templates/header.php';

//  LOGIC PHÂN TRANG 

// 1. Cài đặt số lượng bản ghi (người dùng) trên mỗi trang
$records_per_page = 10;

// 2. Lấy trang hiện tại từ URL, nếu không có thì mặc định là trang 1
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// 3. Tính toán OFFSET (vị trí bắt đầu lấy dữ liệu)
$offset = ($current_page - 1) * $records_per_page;

// 4. Lấy tổng số người dùng trong database
$total_records_result = $conn->query("SELECT COUNT(id) AS total FROM nguoidung");
$total_records = $total_records_result->fetch_assoc()['total'];

// 5. Tính tổng số trang
$total_pages = ceil($total_records / $records_per_page);

// BƯỚC 3: LẤY DỮ LIỆU NGƯỜI DÙNG CHO TRANG HIỆN TẠI
$stmt = $conn->prepare("SELECT id, ho_ten, email, so_dien_thoai, vai_tro, ngay_tao FROM nguoidung ORDER BY id DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result_users = $stmt->get_result();

?>

<?php require_once 'templates/sidebar.php'; ?>

<div class="page-header">
    <h1>Quản lý Người dùng</h1>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_users->num_rows > 0): ?>
                        <?php while($user = $result_users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['ho_ten']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['so_dien_thoai']); ?></td>
                                <td><?php echo $user['vai_tro'] == 'quan_tri' ? '<strong>Quản trị viên</strong>' : 'Người dùng'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($user['ngay_tao'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="btn btn-sm btn-edit">Sửa</a>
                                        <a href="nguoi_dung_xoa.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">Không có người dùng nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="quan_ly_nguoi_dung.php?page=<?php echo $current_page - 1; ?>">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="quan_ly_nguoi_dung.php?page=<?php echo $i; ?>" class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="quan_ly_nguoi_dung.php?page=<?php echo $current_page + 1; ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>