<?php 
// Nhúng header (đã có sẵn code kiểm tra đăng nhập)
require_once 'templates/header.php';
// Kết nối CSDL
require_once '../core/db_connection.php';

// === LẤY DỮ LIỆU THỐNG KÊ ===
// 1. Tổng số phim
$total_movies = $conn->query("SELECT COUNT(id) as total FROM Phim")->fetch_assoc()['total'];

// 2. Tổng số người dùng
$total_users = $conn->query("SELECT COUNT(id) as total FROM NguoiDung")->fetch_assoc()['total'];

// 3. Tổng số suất chiếu sắp tới
$total_showtimes = $conn->query("SELECT COUNT(id) as total FROM LichChieu WHERE thoi_gian_bat_dau >= NOW()")->fetch_assoc()['total'];

// 4. Tổng số vé đã đặt
$total_bookings = $conn->query("SELECT COUNT(id) as total FROM DatVe")->fetch_assoc()['total'];

// 5. Lấy 5 đơn đặt vé mới nhất
$latest_bookings_sql = "
    SELECT d.id, n.ho_ten, p.tieu_de, d.ngay_dat, d.tong_tien
    FROM DatVe d
    JOIN NguoiDung n ON d.id_nguoi_dung = n.id
    JOIN LichChieu l ON d.id_lich_chieu = l.id
    JOIN Phim p ON l.id_phim = p.id
    ORDER BY d.ngay_dat DESC
    LIMIT 5
";
$latest_bookings_result = $conn->query($latest_bookings_sql);
?>

<?php require_once 'templates/sidebar.php'; // Nhúng sidebar ?>

<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="stat-cards">
    <div class="card">
        <div class="card-icon"><i class="fas fa-film"></i></div>
        <div class="card-info">
            <p>Tổng số phim</p>
            <h3><?php echo $total_movies; ?></h3>
        </div>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fas fa-users"></i></div>
        <div class="card-info">
            <p>Tổng số người dùng</p>
            <h3><?php echo $total_users; ?></h3>
        </div>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
        <div class="card-info">
            <p>Suất chiếu sắp tới</p>
            <h3><?php echo $total_showtimes; ?></h3>
        </div>
    </div>
    <div class="card">
        <div class="card-icon"><i class="fas fa-ticket-alt"></i></div>
        <div class="card-info">
            <p>Tổng vé đã đặt</p>
            <h3><?php echo $total_bookings; ?></h3>
        </div>
    </div>
</div>

<div class="recent-bookings">
    <h2>Đơn đặt vé mới nhất</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Người đặt</th>
                <th>Tên phim</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($latest_bookings_result->num_rows > 0): ?>
                <?php while($booking = $latest_bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $booking['id']; ?></td>
                        <td><?php echo htmlspecialchars($booking['ho_ten']); ?></td>
                        <td><?php echo htmlspecialchars($booking['tieu_de']); ?></td>
                        <td><?php echo date('H:i d/m/Y', strtotime($booking['ngay_dat'])); ?></td>
                        <td><?php echo number_format($booking['tong_tien'], 0, ',', '.'); ?>đ</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Chưa có đơn đặt vé nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
require_once 'templates/footer.php'; // Nhúng footer
$conn->close();
?>