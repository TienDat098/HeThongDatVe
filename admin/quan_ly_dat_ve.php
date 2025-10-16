<?php 
// === BƯỚC 1: NHÚNG FILE CỐT LÕI VÀ KẾT NỐI DB ===
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// === BƯỚC 2: LOGIC PHÂN TRANG ===
$records_per_page = 10; // 10 đơn đặt vé mỗi trang
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Lấy tổng số đơn đặt vé
$total_records = $conn->query("SELECT COUNT(id) AS total FROM DatVe")->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// === BƯỚC 3: LẤY DỮ LIỆU ĐẶT VÉ CHO TRANG HIỆN TẠI ===
// Đây là câu truy vấn phức tạp dùng JOIN để lấy thông tin từ nhiều bảng
$sql = "
    SELECT 
        d.id AS id_dat_ve,
        d.ngay_dat,
        d.tong_tien,
        d.trang_thai,
        n.ho_ten AS ten_nguoi_dung,
        p.tieu_de AS ten_phim,
        l.thoi_gian_bat_dau
    FROM DatVe d
    JOIN NguoiDung n ON d.id_nguoi_dung = n.id
    JOIN LichChieu l ON d.id_lich_chieu = l.id
    JOIN Phim p ON l.id_phim = p.id
    ORDER BY d.ngay_dat DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result_bookings = $stmt->get_result();

?>

<?php require_once 'templates/sidebar.php'; // Nhúng sidebar ?>

<div class="page-header">
    <h1>Quản lý Đặt vé</h1>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Tên Phim</th>
                        <th>Suất chiếu</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_bookings->num_rows > 0): ?>
                        <?php while($booking = $result_bookings->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['id_dat_ve']; ?></td>
                                <td><?php echo htmlspecialchars($booking['ten_nguoi_dung']); ?></td>
                                <td><?php echo htmlspecialchars($booking['ten_phim']); ?></td>
                                <td><?php echo date('H:i d/m/Y', strtotime($booking['thoi_gian_bat_dau'])); ?></td>
                                <td><?php echo date('H:i d/m/Y', strtotime($booking['ngay_dat'])); ?></td>
                                <td><?php echo number_format($booking['tong_tien'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <?php if ($booking['trang_thai'] == 'da_dat'): ?>
                                        <span class="status status-booked">Đã đặt</span>
                                    <?php else: ?>
                                        <span class="status status-cancelled">Đã hủy</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($booking['trang_thai'] == 'da_dat'): ?>
                                            <a href="dat_ve_huy.php?id=<?php echo $booking['id_dat_ve']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bạn có chắc chắn muốn hủy vé này?');">Hủy vé</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">Chưa có đơn đặt vé nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="quan_ly_dat_ve.php?page=<?php echo $current_page - 1; ?>">&laquo;</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="quan_ly_dat_ve.php?page=<?php echo $i; ?>" class="<?php echo ($i == $current_page) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                <?php if ($current_page < $total_pages): ?>
                    <a href="quan_ly_dat_ve.php?page=<?php echo $current_page + 1; ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>