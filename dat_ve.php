<?php
require_once 'core/db_connection.php';
require_once 'templates/header.php';

$lich_id = isset($_GET['lich_id']) ? intval($_GET['lich_id']) : 0;

if (!$lich_id) {
    echo '<div class="container"><p>Liên kết không hợp lệ. Vui lòng chọn một suất chiếu.</p></div>';
    require_once 'templates/footer.php';
    exit;
}

// Fetch show info and movie
$sql = "SELECT L.*, P.tieu_de, P.poster, R.ten_rap FROM LichChieu L 
        LEFT JOIN Phim P ON L.id_phim = P.id
        LEFT JOIN PhongChieu PC ON L.id_phong = PC.id
        LEFT JOIN RapChieu R ON PC.id_rap = R.id
        WHERE L.id = " . $lich_id . " LIMIT 1";

$res = $conn->query($sql);
if (!$res || $res->num_rows == 0) {
    echo '<div class="container"><p>Suất chiếu không tồn tại.</p></div>';
    require_once 'templates/footer.php';
    exit;
}

$show = $res->fetch_assoc();
$poster = !empty($show['poster']) ? (preg_match('#^https?://#i', $show['poster']) ? $show['poster'] : BASE_URL . 'assets/images/posters/' . $show['poster']) : BASE_URL . 'assets/images/posters/placeholder.svg';

?>

<div class="container" style="max-width:900px;margin:20px auto;background:#fff;padding:18px;border-radius:8px;box-shadow:0 8px 30px rgba(0,0,0,0.06)">
    <div style="display:flex;gap:16px;align-items:flex-start">
        <div style="flex:0 0 200px">
            <img src="<?php echo $poster; ?>" style="width:200px;height:300px;object-fit:cover;border-radius:6px">
        </div>
        <div style="flex:1">
            <h2 style="margin:0 0 8px"><?php echo htmlspecialchars($show['tieu_de']); ?></h2>
            <p style="color:#666;margin:0 0 8px">Rạp: <?php echo htmlspecialchars($show['ten_rap']); ?></p>
            <p style="color:#666;margin:0 0 12px">Thời gian: <?php echo date('H:i d/m/Y', strtotime($show['thoi_gian_bat_dau'])); ?></p>
            <p style="font-size:18px;font-weight:700;margin:0 0 12px">Giá vé: <?php echo number_format($show['gia_ve'],0,',','.'); ?> đ</p>

            <form method="post" action="">
                <label>Số lượng: <input type="number" name="so_luong" value="1" min="1" style="width:80px"></label>
                <div style="margin-top:12px">
                    <button class="buy" type="button" onclick="alert('Demo: chức năng đặt vé chưa được bật.');">Đặt vé</button>
                    <a href="chi_tiet_phim.php?id=<?php echo $show['id_phim']; ?>" style="margin-left:10px">Xem chi tiết phim</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'templates/footer.php';
$conn->close();
?>