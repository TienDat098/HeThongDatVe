<?php 
require_once '../core/db_connection.php';
require_once 'templates/header.php';

// === LOGIC LỌC NGÀY THÁNG ===
// Đặt ngày mặc định (ví dụ: 1 tháng qua)
$ngay_bat_dau = $_GET['start_date'] ?? date('Y-m-01');
$ngay_ket_thuc = $_GET['end_date'] ?? date('Y-m-t'); // 't' = ngày cuối cùng của tháng

// === LẤY DỮ LIỆU BÁO CÁO ===
$stmt = $conn->prepare("
    SELECT 
        p.tieu_de, 
        COUNT(d.id) AS so_ve_ban, 
        SUM(d.tong_tien) AS tong_doanh_thu
    FROM DatVe d
    JOIN LichChieu l ON d.id_lich_chieu = l.id
    JOIN Phim p ON l.id_phim = p.id
    WHERE d.trang_thai = 'da_dat' 
      AND DATE(d.ngay_dat) BETWEEN ? AND ?
    GROUP BY p.id, p.tieu_de
    ORDER BY tong_doanh_thu DESC
");
$stmt->bind_param("ss", $ngay_bat_dau, $ngay_ket_thuc);
$stmt->execute();
$result_report = $stmt->get_result();

// Tính tổng
$total_revenue = 0;
$total_tickets = 0;
$report_data = [];
while ($row = $result_report->fetch_assoc()) {
    $report_data[] = $row;
    $total_revenue += $row['tong_doanh_thu'];
    $total_tickets += $row['so_ve_ban'];
}
?>

<?php require_once 'templates/sidebar.php'; ?>

<div class="page-header">
    <h1>Báo cáo Doanh thu</h1>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header report-filters">
        <form action="bao_cao.php" method="get">
            <div class="form-group">
                <label>Từ ngày</label>
                <input type="date" name="start_date" value="<?php echo $ngay_bat_dau; ?>">
                 </div>
            <div class="form-group">
            <label>Đến ngày</label>
            <input type="date" name="end_date" value="<?php echo $ngay_ket_thuc; ?>">
            </div>
            <div class="report-buttons">
             <button type="submit" class="btn btn-primary">Xem Báo cáo</button>
                 <button type="button" class="btn btn-secondary" onclick="window.print();">In Báo cáo</button>
            </div>
        </form>
        </div>

        <div class="card-body">
            <div class="report-summary">
                <h4>Tổng quan từ <?php echo $ngay_bat_dau; ?> đến <?php echo $ngay_ket_thuc; ?></h4>
                <p>Tổng doanh thu: <strong><?php echo number_format($total_revenue, 0, ',', '.'); ?>đ</strong></p>
                <p>Tổng số vé đã bán: <strong><?php echo $total_tickets; ?></strong></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Hạng</th>
                        <th>Tên Phim</th>
                        <th>Số vé đã bán</th>
                        <th>Tổng doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($report_data) > 0): ?>
                        <?php $rank = 1; ?>
                        <?php foreach($report_data as $item): ?>
                            <tr>
                                <td><?php echo $rank++; ?></td>
                                <td><?php echo htmlspecialchars($item['tieu_de']); ?></td>
                                <td><?php echo $item['so_ve_ban']; ?></td>
                                <td><?php echo number_format($item['tong_doanh_thu'], 0, ',', '.'); ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Không có dữ liệu trong khoảng thời gian này.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
require_once 'templates/footer.php';
$conn->close();
?>